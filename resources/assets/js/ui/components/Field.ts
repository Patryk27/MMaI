import { Componentable } from '../concerns/Componentable';
import { Feedbackable } from '../concerns/Feedbackable';
import { Serializable } from '../concerns/Serializable';
import { Input } from './Input';
import { Select } from './Select';

interface Fieldable extends Componentable, Feedbackable, Serializable {

}

export class Field implements Fieldable {

    constructor(
        private readonly name: string,
        private readonly component: Fieldable,
    ) {

    }

    public getName(): string {
        return this.name;
    }

    public getComponent(): Fieldable {
        return this.component;
    }

    public as<T>(): T {
        return <T><unknown>this.component;
    }

    public focus(): void {
        this.component.focus();
    }

    public on(event: string, handler: (...args: any) => void): void {
        this.component.on(event, handler);
    }

    public setFeedback(type: string, message: string): void {
        this.component.setFeedback(type, message);
    }

    public clearFeedback(): void {
        this.component.clearFeedback();
    }

    public serialize(): object {
        return {
            [this.name]: this.component.serialize(),
        };
    }

    public static input(name: string, inputContainer: JQuery, inputName: string = name): Field {
        return new Field(name, new Input(
            inputContainer.find(`[name="${inputName}"]`),
        ));
    }

    public static select(name: string, selectContainer: JQuery, selectName: string = name): Field {
        return new Field(name, new Select(
            selectContainer.find(`[name="${selectName}"]`),
        ));
    }

}
