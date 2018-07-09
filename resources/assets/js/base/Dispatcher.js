import $ from 'jquery';

export default new class Dispatcher {

    constructor() {
        this.$handlers = [];
    }

    /**
     * Registers a function which will be called when given view is active.
     *
     * @param {string} viewClass
     * @param {function} viewHandler
     */
    register(viewClass, viewHandler) {
        this.$handlers.push({
            viewClass,
            viewHandler,
        });
    }

    /**
     * Executes all handlers for current view.
     */
    execute() {
        const $body = $('body');

        this.$handlers.forEach(({viewClass, viewHandler}) => {
            if ($body.hasClass(viewClass)) {
                viewHandler();
            }
        });
    }

}