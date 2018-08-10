/**
 * This class models a Button component, which can be used to build UI-friendly buttons.
 *
 * It assumes that button already exists in the DOM (that's why it accepts a selector) and then allows to e.g. disable
 * it or create a dynamic spinner inside it.
 */
export default class ButtonComponent {

    /**
     * @param {jQuery|string} selector
     */
    constructor(selector) {
        this.$dom = {
            button: $(selector),
        };
    }

    /**
     * Blocks the button, yielding it unclickable.
     */
    block() {
        this.$dom.button.attr('disabled', true);
    }

    /**
     * Unblocks the button, making it clickable again.
     */
    unblock() {
        this.$dom.button.attr('disabled', false);
    }

    /**
     * Shows a spinner inside the button.
     */
    showSpinner() {
        this.$dom.spinner =
            $('<i>')
                .addClass('fa fa-circle-notch fa-spin')
                .prependTo(this.$dom.button);
    }

    /**
     * Hides spinner created from the previous {@see showSpinner} call.
     */
    hideSpinner() {
        if (this.$dom.hasOwnProperty('spinner')) {
            this.$dom.spinner.remove();
            delete this.$dom.spinner;
        }
    }

}