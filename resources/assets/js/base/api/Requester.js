import axios from 'axios';

export default class Requester {

    /**
     * Executes a request to the API.
     *
     * If API returns an error, that error is initially parsed for convenience.
     *
     * Example usage
     * -------------
     *
     * ```javascript
     * Requester
     *   .execute({
     *     method: 'post',
     *     url: '/somewhere',
     *
     *     data: {
     *          hello: true,
     *          world: false,
     *     },
     *   })
     *   .then((response) => {
     *     // do something with response
     *   })
     *   .catch((error) => {
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
        } catch (ex) {
            console.error('Request at [', request.method, request.url, '] failed with following exception: ', ex);

            // If exception did not come from the Axios or the exception is not about the request's response, lets just
            // pass it to the caller without further ado.
            if (!ex.hasOwnProperty('response')) {
                throw {
                    type: 'exception',
                    message: ex.toString(),
                    payload: ex,
                };
            }

            const response = ex.response;

            switch (response.status) {
                case 413:
                    Requester.$handlePayloadTooLarge(response);
                    break;

                case 422:
                    Requester.$handleUnprocessableEntity(response);
                    break;

                case 500:
                    Requester.$handleInternalServerError(response);
                    break;

                default:
                    throw {
                        type: 'exception',
                        message: ex.toString(),
                        payload: ex,
                    };
            }
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
     * const uploader = Requester.executeProgressable({
     *   method: 'post',
     *   url: '/somewhere',
     *   data,
     * });
     *
     * uploader.onSuccess(() => {
     *   console.log('File has been uploaded.');
     * });
     *
     * uploader.onFailure((error) => {
     *   console.error(error);
     * });
     *
     * uploader.onProgress(({ uploadedPercentage }) => {
     *   console.log(`Uploaded ${uploadedPercentage}%`);
     * });
     * ```
     *
     * @param {object} request
     * @returns {{onSuccess: function, onFailure: function, onProgress: function}}
     */
    static executeProgressable(request) {
        const eventHandlers = {
            success: _ => _,
            failure: _ => _,
            progress: _ => _,
        };

        request.onUploadProgress = (evt) => {
            eventHandlers.progress({
                uploadedBytes: evt.loaded,
                totalBytes: evt.total,

                uploadedPercentage: Math.floor(100 * evt.loaded / evt.total),
            });
        };

        Requester
            .execute(request)
            .then((response) => eventHandlers.success(response))
            .catch((error) => eventHandlers.failure(error));

        return {
            onSuccess(fn) {
                eventHandlers.success = fn;
                return this;
            },

            onFailure(fn) {
                eventHandlers.failure = fn;
                return this;
            },

            onProgress(fn) {
                eventHandlers.progress = fn;
                return this;
            },
        };
    }

    /**
     * @private
     */
    static $handlePayloadTooLarge() {
        throw {
            type: 'invalid-input',
            message: 'Supplied file was too big - please try uploading a smaller one.',
        };
    }

    /**
     * @private
     *
     * @param {AxiosResponse} response
     */
    static $handleUnprocessableEntity({ data }) {
        if (!data.hasOwnProperty('message')) {
            throw {
                type: 'exception',
                message: 'There has been a fatal error - please refresh the page and try again.',
            };
        }

        throw {
            type: 'invalid-input',
            message: data.message,
            payload: data.errors,
        };
    }

    /**
     * @private
     *
     * @param {AxiosResponse} response
     */
    static $handleInternalServerError({ data }) {
        if (data.hasOwnProperty('message')) {
            throw {
                type: 'exception',
                message: data.message,
            };
        }

        throw {
            type: 'exception',
            message: 'There has been a fatal error - please refresh the page and try again.',
        };
    }

}
