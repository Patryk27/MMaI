import axios from 'axios';
import Error from './Error';

export default class Client {

    /**
     * Executes a request to the API.
     *
     * # Example usage
     *
     * ```javascript
     * Client
     *   .execute({
     *     method: 'post',
     *     url: '/somewhere',
     *     data: {
     *          hello: true,
     *          world: false,
     *     },
     *   })
     *   .then((response: any) => {
     *     // do something with response
     *   })
     *   .catch((error: Error) => {
     *     // do something with error
     *   });
     * ```
     *
     * @param {object} request
     * @returns {Promise<*>}
     */
    static async execute(request) {
        try {
            return await axios.request(request);
        } catch (error) {
            console.error(`Request at [${request.method} ${request.url}] failed with following exception:`, error);

            if (error.hasOwnProperty('response')) {
                const response = error.response;

                switch (response.status) {
                    case 413:
                        Client.$handlePayloadTooLarge(response);
                        return;

                    case 422:
                        Client.$handleUnprocessableEntity(response);
                        return;

                    case 500:
                        Client.$handleInternalServerError(response);
                        return;
                }
            }

            throw new Error('exception', error.toString(), error);
        }
    }

    /**
     * Executes a "progressable" request to the API - that is: a request which uploading progress is tracked.
     * Useful for uploading files.
     *
     * Contrary to the {@see execute()} method, this one does not return a {@see Promise}, but rather an object allowing
     * to mount at various request-lifecycle-related events.
     *
     * Example usage
     * -------------
     *
     * ```javascript
     * const data = new FormData();
     *
     * data.append('some_file', someFile);
     *
     * const uploader = Client.executeProgressable({
     *   method: 'post',
     *   url: '/somewhere',
     *   data,
     * });
     *
     * uploader.onProgress(({ uploadedPercentage }) => {
     *   console.log(`Uploaded ${uploadedPercentage}%`);
     * });
     *
     * uploader.onSuccess(() => {
     *   console.log('File has been uploaded.');
     * });
     *
     * uploader.onFailure((error) => {
     *   console.error(error);
     * });
     * ```
     *
     * @param {object} request
     * @returns {{onProgress: function, onSuccess: function, onFailure: function}}
     */
    static executeProgressable(request) {
        const eventHandlers = {
            progress: _ => _,
            success: _ => _,
            failure: _ => _,
        };

        request.onUploadProgress = (evt) => {
            eventHandlers.progress({
                uploadedBytes: evt.loaded,
                totalBytes: evt.total,
                uploadedPercentage: Math.floor(100 * evt.loaded / evt.total),
            });
        };

        Client
            .execute(request)
            .then((response) => eventHandlers.success(response))
            .catch((error) => eventHandlers.failure(error));

        return {
            onProgress(fn) {
                eventHandlers.progress = fn;
                return this;
            },

            onSuccess(fn) {
                eventHandlers.success = fn;
                return this;
            },

            onFailure(fn) {
                eventHandlers.failure = fn;
                return this;
            },
        };
    }

    /**
     * @private
     */
    static $handlePayloadTooLarge() {
        throw new Error(
            'invalid-input',
            'Supplied file was too big - please try uploading a smaller one.',
        );
    }

    /**
     * @private
     *
     * @param {AxiosResponse} response
     */
    static $handleUnprocessableEntity({ data }) {
        // noinspection JSUnresolvedVariable
        throw new Error(
            'invalid-input',
            data.message || 'There has been an error trying to process your request - please refresh the page and try again.',
            data.errors,
        );
    }

    /**
     * @private
     *
     * @param {AxiosResponse} response
     */
    static $handleInternalServerError({ data }) {
        throw new Error(
            'exception',
            data.message || 'There has been an error trying to process your request - please refresh the page and try again.',
        );
    }

}
