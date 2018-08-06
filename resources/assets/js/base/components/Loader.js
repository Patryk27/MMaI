export default class Loader {

    /**
     * @param {jQuery|string} selector
     */
    constructor(selector) {
        if (typeof selector === 'string') {
            selector = $(selector);
        }

        this.$dom = {
            container: selector,
        };

        this.$dom.container.addClass('loader');

        // Create the loader
        this.$dom.loader =
            $('<div>')
                .addClass('loader')
                .addClass('loader-' + this.$dom.container.data('loader-type'))
                .appendTo(this.$dom.container);

        // Create the overlay
        this.$dom.overlay =
            $('<div>')
                .addClass('loader-overlay')
                .appendTo(this.$dom.container);
    }

    show() {
        this.$dom.container.addClass('active');
    }

    hide() {
        this.$dom.container.removeClass('active');
    }

}