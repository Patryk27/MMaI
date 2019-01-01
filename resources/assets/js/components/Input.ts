import { Component } from './Component';

export class Input extends Component {

    protected dom: {
        element: JQuery,
        feedback?: JQuery,
    };

    /**
     * Adds a feedback to the input.
     *
     * Example:
     *   setFeedback('valid', 'This input is correct.');
     *   setFeedback('invalid', 'This input is not correct.');
     */
    public setFeedback(type: string, message: string): void {
        if (this.dom.feedback) {
            this.removeFeedback();
        }

        const isTypeValid = type === 'valid';

        this.dom.element
            .removeClass('is-invalid is-valid')
            .addClass(isTypeValid ? 'is-valid' : 'is-invalid');

        this.dom.feedback =
            $('<div>')
                .addClass(isTypeValid ? 'valid-feedback' : 'invalid-feedback')
                .text(message)
                .appendTo(
                    this.dom.element.parent(),
                );
    }

    public removeFeedback(): void {
        if (this.dom.feedback) {
            this.dom.feedback.remove();
            delete this.dom.feedback;
        }
    }

    public getValue(): any {
        return this.dom.element.val();
    }

    public setValue(value: any): void {
        this.dom.element.val(value);
    }

}
