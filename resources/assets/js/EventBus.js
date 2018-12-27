export default class EventBus {

    constructor() {
        this.$handlers = {};
    }

    /**
     * Binds a handler to be run when given event is emitted.
     *
     * Example:
     *   bus.on('something', () => alert('Something happened!'))
     *   bus.emit('something')
     *
     * @param {string} eventName
     * @param {function} eventHandler
     */
    on(eventName, eventHandler) {
        if (!this.$handlers.hasOwnProperty(eventName)) {
            this.$handlers[eventName] = [];
        }

        this.$handlers[eventName].push(eventHandler);
    }

    /**
     * Binds a handler to be run when any of given events is emitted.
     *
     * Example:
     *   bus.onAny(['something', 'something-else'], () => alert('Something (else) happened!'))
     *   bus.emit('something')
     *   bus.emit('something-else')
     *
     * @param {array<string>} eventNames
     * @param {function} eventHandler
     */
    onAny(eventNames, eventHandler) {
        eventNames.forEach((eventName) => {
            this.on(eventName, eventHandler);
        });
    }

    /**
     * Emits given event.
     *
     * @param {string} eventName
     * @param {*} eventPayload
     */
    emit(eventName, eventPayload = {}) {
        if (this.$handlers.hasOwnProperty(eventName)) {
            this.$handlers[eventName].forEach((eventHandler) => {
                eventHandler(eventPayload);
            });
        }
    }

}
