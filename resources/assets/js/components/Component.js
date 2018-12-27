export default class Component {

    /**
     * @param {jQuery|string} selector
     */
    constructor(selector) {
        this.$dom = {
            el: $(selector),
        };
    }

    /**
     * Binds a handler to given component's event.
     *
     * @param {string} eventName
     * @param {function} eventHandler
     */
    on(eventName, eventHandler) {
        this.$dom.el.on(eventName, eventHandler);
    }

    /**
     * Focuses on the component.
     */
    focus() {
        this.$dom.el.focus();
    }

    /**
     * Unblocks the component.
     *
     * @param {boolean} enabled
     */
    enable(enabled = true) {
        this.$dom.el.attr('disabled', !enabled);
    }

    /**
     * Blocks the component.
     *
     * @param {boolean} disabled
     */
    disable(disabled = true) {
        this.enable(!disabled);
    }

    /**
     * Returns `true` if the component exists in the DOM.
     *
     * @returns {boolean}
     */
    exists() {
        return this.$dom.el.length > 0;
    }

}
