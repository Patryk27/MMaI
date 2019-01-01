import { Component } from './Component';

export class Button extends Component {

    protected dom: {
        element: JQuery,
        spinner?: JQuery,
    };

    /**
     * Shows a spinner inside the button.
     */
    public showSpinner(): void {
        this.dom.spinner =
            $('<i>')
                .addClass('fa fa-circle-notch fa-spin')
                .prependTo(this.dom.element);
    }

    /**
     * Hides spinner created from the previous {@see showSpinner} call.
     */
    public hideSpinner(): void {
        if (this.dom.spinner) {
            this.dom.spinner.remove();
            delete this.dom.spinner;
        }
    }

}
