interface Handlers {
    [eventName: string]: Array<(...args: any) => void>;
}

export class EventBus {

    private handlers: Handlers = {};

    /**
     * Binds a handler to be run when given event is emitted.
     *
     * Example:
     *   bus.on('something', () => alert('Something happened!'))
     *   bus.emit('something')
     */
    on(eventNames: string | Array<string>, eventHandler: (...args: any) => void) {
        if (!Array.isArray(eventNames)) {
            eventNames = [eventNames];
        }

        eventNames.forEach((eventName) => {
            if (!this.handlers.hasOwnProperty(eventName)) {
                this.handlers[eventName] = [];
            }

            this.handlers[eventName].push(eventHandler);
        });
    }

    /**
     * Executes all handlers bound to given event.
     */
    emit(eventName: string, eventPayload: any = {}) {
        if (this.handlers.hasOwnProperty(eventName)) {
            this.handlers[eventName].forEach((eventHandler) => {
                eventHandler(eventPayload);
            });
        }
    }

}
