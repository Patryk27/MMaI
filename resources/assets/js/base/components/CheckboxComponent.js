export default class CheckboxComponent {

    /**
     * @param {jQuery|string} selector
     */
    constructor(selector) {
        this.$dom = {
            checkbox: $(selector),
        };
    }

    /**
     * Blocks the checkbox, yielding it uneditable.
     */
    block() {
        this.$dom.checkbox.attr('disabled', true);
    }

    /**
     * Unblocks the checkbox, making it editable again.
     */
    unblock() {
        this.$dom.checkbox.attr('disabled', false);
    }

    /**
     * Binds given handler to the checkbox's event.
     *
     * @param {string} eventName
     * @param {function} eventHandler
     */
    on(eventName, eventHandler) {
        this.$dom.checkbox.on(eventName, eventHandler);
    }

    /**
     * Returns `true` if this checkbox currently exists in the DOM.
     *
     * @returns {boolean}
     */
    exists() {
        return this.$dom.checkbox.length > 0;
    }

    /**
     * Returns `true` if this checkbox is currently checked.
     *
     * @returns {boolean}
     */
    isChecked() {
        return this.$dom.checkbox.is(':checked');
    }

}