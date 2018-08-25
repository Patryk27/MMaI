import Component from './Component';

export default class ButtonComponent extends Component {

    /**
     * Shows a spinner inside the button.
     */
    showSpinner() {
        this.$dom.spinner =
            $('<i>')
                .addClass('fa fa-circle-notch fa-spin')
                .prependTo(this.$dom.el);
    }

    /**
     * Hides spinner created from the previous {@see showSpinner} call.
     */
    hideSpinner() {
        if (this.$dom.hasOwnProperty('spinner')) {
            this.$dom.spinner.remove();
            delete this.$dom.spinner;
        }
    }

}