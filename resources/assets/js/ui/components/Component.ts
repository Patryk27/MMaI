import { Enablable } from '../concerns/Enablable';
import { Eventable } from '../concerns/Eventable';
import { Focusable } from '../concerns/Focusable';

export abstract class Component implements Enablable, Eventable, Focusable {

    protected handle: JQuery;

    constructor(selector: any) {
        this.handle = $(selector);
    }

    public enable(): void {
        this.handle.prop('disabled', false);
    }

    public disable(): void {
        this.handle.prop('disabled', true);
    }

    public setIsEnabled(isEnabled: boolean): void {
        if (isEnabled) {
            this.enable();
        } else {
            this.disable();
        }
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
