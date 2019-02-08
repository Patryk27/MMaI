type ShownEventHandler = () => void;

export class Modal {

    private readonly modal: JQuery;

    private readonly eventHandlers: {
        shown?: ShownEventHandler,
    } = {};

    constructor(selector: any) {
        this.modal = $(selector);

        this.modal.on('shown.bs.modal', () => {
            if (this.eventHandlers.shown) {
                this.eventHandlers.shown();
            }
        });
    }

    /**
     * Shows the modal.
     */
    public show(): void {
        // @ts-ignore
        this.modal.modal();
    }

    /**
     * Hides the modal.
     */
    public hide(): void {
        // @ts-ignore
        this.modal.modal('hide');
    }

    /**
     * Registers a callback that will be fired each time the modal is shown.
     */
    public onShow(fn: ShownEventHandler): void {
        this.eventHandlers.shown = fn;
    }

}
