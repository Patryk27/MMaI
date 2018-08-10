export default class Bus {

    constructor() {
        this.$eventHandlers = {};
    }

    /**
     * @param {string} eventName
     * @param {function} eventHandler
     */
    on(eventName, eventHandler) {
        if (!this.$eventHandlers.hasOwnProperty(eventName)) {
            this.$eventHandlers[eventName] = [];
        }

        this.$eventHandlers[eventName].push(eventHandler);
    }

    /**
     * @param {string} eventName
     * @param {*} eventPayload
     */
    emit(eventName, eventPayload = {}) {
        if (this.$eventHandlers.hasOwnProperty(eventName)) {
            this.$eventHandlers[eventName].forEach((eventHandler) => {
                eventHandler(eventPayload);
            });
        }
    }

}