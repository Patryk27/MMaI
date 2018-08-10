export default class InputComponent {

    /**
     * @param {jQuery|string} selector
     */
    constructor(selector) {
        this.$dom = {
            input: $(selector),
        };
    }

    /**
     * Focuses on the input.
     */
    focus() {
        this.$dom.input.focus();
    }

    /**
     * Blocks the input, yielding it uneditable.
     */
    block() {
        this.$dom.input.attr('disabled', true);
    }

    /**
     * Unblocks the input, making it editable again.
     */
    unblock() {
        this.$dom.input.attr('disabled', false);
    }

    /**
     * Returns input's value.
     *
     * @returns {*}
     */
    getValue() {
        return this.$dom.input.val();
    }

}