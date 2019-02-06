import { Componentable } from '../concerns/Componentable';

export abstract class Component implements Componentable {

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

    public getHandle(): JQuery {
        return this.handle;
    }

}
