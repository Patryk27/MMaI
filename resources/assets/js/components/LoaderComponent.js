export default class LoaderComponent {

    /**
     * @param {jQuery|string} selector
     */
    constructor(selector) {
        this.$dom = {
            container: $(selector),
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

    /**
     * Shows the loader.
     */
    show() {
        this.$dom.container.addClass('active');
    }

    /**
     * Hides the loader.
     */
    hide() {
        this.$dom.container.removeClass('active');
    }

}