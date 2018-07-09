export default class MessageBag {

    /**
     * @param {jQuery} $container
     */
    constructor($container) {
        this.$dom = {
            container: $container,
        };
    }

    /**
     * Adds an error message into the message bag.
     *
     * @param {string} message
     */
    addError(message) {
        const $msg = $('<div>');

        $msg.addClass('alert alert-danger');
        $msg.text(message);

        $msg.appendTo(this.$dom.container);
    }

    /**
     * Removes all messages from the message bag.
     */
    clear() {
        this.$dom.container.find('div').remove();
    }

    /**
     * Scrolls page to the top of the message bag.
     */
    scrollTo() {
        $('html, body').animate({
            scrollTop: this.$dom.container.offset().top - 15,
        }, 1000);
    }

}