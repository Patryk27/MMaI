import { Component } from './Component';

export class Button extends Component {

    private spinner?: JQuery;

    public showSpinner(): void {
        this.spinner =
            $('<i>')
                .addClass('fa fa-circle-notch fa-spin')
                .prependTo(this.handle);
    }

    public hideSpinner(): void {
        if (this.spinner) {
            this.spinner.remove();
            delete this.spinner;
        }
    }

}
