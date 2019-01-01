export abstract class Component {

    protected dom: {
        element: JQuery,
    };

    constructor(selector: any) {
        this.dom = {
            element: $(selector),
        };
    }

    public on(event: string, handler: () => void): void {
        this.dom.element.on(event, handler);
    }

    public focus(): void {
        this.dom.element.focus();
    }

    public enable(enabled: boolean = true): void {
        // @ts-ignore
        this.dom.element.attr('disabled', !enabled);
    }

    public disable(disabled: boolean = true): void {
        this.enable(!disabled);
    }

    public exists(): boolean {
        return this.dom.element.length > 0;
    }

}
