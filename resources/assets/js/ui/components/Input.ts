import { Component } from './Component';

export class Input extends Component {

    private feedback?: JQuery;

    public constructor(selector: any) {
        super(selector);

        this.on('change, keypress', () => {
            this.clearFeedback();
        });
    }

    public setFeedback(type: string, message: string): void {
        this.clearFeedback();

        const isValid = type === 'valid';

        this.handle.addClass(isValid ? 'is-valid' : 'is-invalid');

        this.feedback =
            $('<div>')
                .addClass(isValid ? 'valid-feedback' : 'invalid-feedback')
                .text(message)
                .appendTo(this.handle.parent());
    }

    public clearFeedback(): void {
        if (this.feedback) {
            this.handle.removeClass('is-invalid is-valid');

            this.feedback.remove();
            this.feedback = null;
        }
    }

    get value(): any {
        return this.handle.val();
    }

    set value(value: any) {
        this.handle.val(value);
    }

    public static fromContainer(container: any, name: string): Input {
        return new Input($(container).find(`[name='${name}']`));
    }

}
