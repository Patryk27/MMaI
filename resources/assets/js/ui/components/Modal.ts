export class Modal {

    private readonly modal: JQuery;
    private state: string;

    public constructor(selector: any) {
        this.modal = $(selector);
        this.state = 'hidden';

        this.modal.on('shown.bs.modal', () => {
            if (this.state === 'hiding') {
                this.hide();
            }

            this.state = 'shown';
        });

        this.modal.on('hidden.bs.modal', () => {
            if (this.state === 'showing') {
                this.show();
            }

            this.state = 'hidden';
        });
    }

    public show(): void {
        this.state = 'showing';

        // @ts-ignore
        this.modal.modal();
    }

    public hide(): void {
        this.state = 'hiding';

        // @ts-ignore
        this.modal.modal('hide');
    }

    public on(event: string, handler: (...args: Array<any>) => void): void {
        this.modal.on(event, handler);
    }

}
