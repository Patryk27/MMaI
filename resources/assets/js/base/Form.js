import axios from 'axios';
import swal from 'sweetalert';

export default class Form {

    /**
     * @param {string} method
     * @param {string} url
     */
    constructor(method, url) {
        this.$config = {
            method,
            url,
        };

        this.$eventHandlers = {
            beforeSubmit: () => void 0,
            afterSubmit: () => void 0,
            fieldError: () => void 0,
        };
    }

    /**
     * Registers a callback to be executed before form is submitted.
     *
     * Callback's signature:
     *     () => void
     *
     * @param {function} handler
     */
    onBeforeSubmit(handler) {
        this.$eventHandlers.beforeSubmit = handler;
    }

    /**
     * Registers a callback to be executed after form has been submitted and
     * response processed.
     *
     * Callback's signature:
     *     (success: bool, response: ?object) => void
     *
     * @param {function} handler
     */
    onAfterSubmit(handler) {
        this.$eventHandlers.afterSubmit = handler;
    }

    /**
     * Registers a callback to be executed when backend returns information
     * that given field contains an invalid value.
     *
     * Callback's signature:
     *     (fieldName: string, fieldMessage: string): void
     *
     * @param handler
     */
    onFieldError(handler) {
        this.$eventHandlers.fieldError = handler;
    }

    /**
     * Submits the form, properly handling the errors.
     *
     * @param {object} data
     */
    submit(data) {
        this.$eventHandlers.beforeSubmit();

        const request = axios.request({
            method: this.$config.method,
            url: this.$config.url,
            data: data,
        });

        request
            .then((response) => {
                this.$eventHandlers.afterSubmit(true, response);
            })
            .catch((ex) => {
                if (this.$handleError(ex)) {
                    swal({
                        title: 'Error',
                        text: 'Form contains errors - please fix them and try again.',
                        icon: 'error',
                    });
                } else {
                    swal({
                        title: 'Fatal error',
                        text: 'Failed to save the form - please refresh the site and try again.',
                        icon: 'error',
                    });
                }

                this.$eventHandlers.afterSubmit(false, null);
            });
    }

    /**
     * @private
     *
     * Handles form errors.
     *
     * Returns `true` if errors have been processed successfully (e.g. when
     * dealing with with "422 Unprocessable Entity" errors), and `false` when
     * errors could not have been handled (e.g. "500 Internal Server Error").
     *
     * @param {object} ex
     * @return {boolean}
     */
    $handleError(ex) {
        if (!ex.hasOwnProperty('response')) {
            console.error('Caught exception is not an object with [response] property - don\'t know what to do:', ex);

            return false;
        }

        const response = ex.response;

        // If we've received a "422 Unprocessable Entity" response code, this
        // means that form contains an error which was properly handled in the
        // backend and we've got at least one error message to show to the user.
        if (response.status !== 422) {
            console.error('Caught exception contains response status different than 422 - don\'t know what to do:', ex);

            return false;
        }

        // Extract errors from the response and parse them
        const
            data = response.data,
            errors = data.errors;

        Object.keys(errors).forEach((fieldName) => {
            if (errors.hasOwnProperty(fieldName)) {
                const fieldMessages = errors[fieldName];

                fieldMessages.forEach((fieldMessage) => {
                    this.$eventHandlers.fieldError(fieldName, fieldMessage);
                });
            }
        });

        return true;
    }

}