import axios from 'axios';

export default class Requester {

    /**
     * Executes a request to the API and initially parses the response (e.g. by extracting the error messages).
     *
     * @param {string} method
     * @param {string} url
     * @param {object} data
     * @returns {Promise<*>}
     */
    static async execute(method, url, data) {
        try {
            return await axios.request({
                method,
                url,
                data,
            });
        } catch (ex) {
            console.error('Executed [', method, '] request at [', url, '] and caught following exception: ', ex);

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
     * @param {AxiosResponse} response
     */
    static $handleUnprocessableEntity({data}) {
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
     * @param {AxiosResponse} response
     */
    static $handleInternalServerError({data}) {
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