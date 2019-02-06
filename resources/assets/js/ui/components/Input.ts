import { Feedbackable } from '../concerns/Feedbackable';
import { Serializable } from '../concerns/Serializable';
import { Component } from './Component';

export class Input extends Component implements Feedbackable, Serializable {

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

    public setValue(value: any): void {
        this.handle.val(value);
    }

    public serialize(): any {
        return this.handle.val();
    }

}
