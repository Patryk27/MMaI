type ShowEventHandler = () => void;

export class Modal {

    private readonly modal: JQuery;

    private readonly eventHandlers: {
        show?: ShowEventHandler,
    } = {};

    constructor(selector: any) {
        this.modal = $(selector);

        this.modal.on('show.bs.modal', () => {
            if (this.eventHandlers.show) {
                this.eventHandlers.show();
            }
        });
    }

    public show(): void {
        // @ts-ignore
        this.modal.modal();
    }

    public hide(): void {
        // @ts-ignore
        this.modal.modal('hide');
    }

    public on(event: string, handler: (...args: any) => void): void {
        this.modal.on(event, handler);
    }

    public onShow(fn: ShowEventHandler): void {
        this.eventHandlers.show = fn;
    }

}
