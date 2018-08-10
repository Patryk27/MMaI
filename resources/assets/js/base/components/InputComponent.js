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
     * Adds a feedback to the input.
     *
     * Example:
     *   setFeedback('valid', 'This input is correct.');
     *   setFeedback('invalid', 'This input is not correct.');
     *
     * @param {string} type
     * @param {string} message
     */
    setFeedback(type, message) {
        // We can only have one feedback - if at least one is present right now, we force-delete it
        if (this.$dom.hasOwnProperty('feedback')) {
            this.removeFeedback();
        }

        // Determine whether we're going to add a "valid" or "invalid" feedback
        const isValid = type === 'valid';

        // Change the input's class
        this.$dom.input
            .removeClass(isValid ? 'is-invalid' : 'is-valid')
            .addClass(isValid ? 'is-valid' : 'is-invalid');

        // Add the feedback's container
        this.$dom.feedback =
            $('<div>')
                .addClass(isValid ? 'valid-feedback' : 'invalid-feedback')
                .text(message)
                .appendTo(
                    this.$dom.input.parent()
                );
    }

    /**
     * Removes this input's feedback, if there is any.
     */
    removeFeedback() {
        if (this.$dom.hasOwnProperty('feedback')) {
            this.$dom.feedback.remove();
            delete this.$dom.feedback;
        }
    }

    /**
     * Returns input's value.
     *
     * @returns {*}
     */
    getValue() {
        return this.$dom.input.val();
    }

    /**
     * Changes input's value.
     *
     * @param {*} value
     */
    setValue(value) {
        this.$dom.input.val(value);
    }

}