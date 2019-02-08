export abstract class Component {

    protected handle: JQuery;

    constructor(selector: any) {
        this.handle = $(selector);
    }

    public enable(enabled: boolean = true): void {
        this.handle.prop('disabled', !enabled);
    }

    public disable(disabled: boolean = true): void {
        this.handle.prop('disabled', disabled);
    }

    public on(event: string, handler: (...args: any) => void): void {
        this.handle.on(event, handler);
    }

    public focus(): void {
        this.handle.focus();
    }

}
