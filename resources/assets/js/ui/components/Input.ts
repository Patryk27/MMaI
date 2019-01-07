import { Feedbackable } from '../concerns/Feedbackable';
import { Valuable } from '../concerns/Valuable';
import { Component } from './Component';

export class Input extends Component implements Feedbackable, Valuable {

    private feedback?: JQuery;

    public addFeedback(type: string, message: string): void {
        if (this.feedback) {
            this.removeFeedback();
        }

        const isValid = type === 'valid';

        this.handle
            .removeClass('is-invalid is-valid')
            .addClass(isValid ? 'is-valid' : 'is-invalid');

        this.feedback =
            $('<div>')
                .addClass(isValid ? 'valid-feedback' : 'invalid-feedback')
                .text(message)
                .appendTo(this.handle.parent());
    }

    public removeFeedback(): void {
        if (this.feedback) {
            this.feedback.remove();
            this.feedback = null;
        }
    }

    public getValue(): any {
        return this.handle.val();
    }

    public setValue(value: any): void {
        this.handle.val(value);
    }

}
