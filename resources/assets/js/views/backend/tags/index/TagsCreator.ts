import swal from 'sweetalert';
import { TagsFacade } from '../../../../api/tags/TagsFacade';
import { Button, Form, Input, Modal } from '../../../../ui/components';
import { EventBus } from '../../../../utils/EventBus';

export class TagsCreator {

    private readonly modal: Modal;
    private readonly form: Form;
    private readonly closeButton: Button;
    private readonly submitButton: Button;

    constructor(private readonly bus: EventBus, modal: JQuery) {
        this.modal = new Modal(modal);

        this.modal.onShown(() => {
            const name = this.form.find('name');

            name.setValue('');
            name.focus();
        });

        this.form = new Form({
            ajax: true,
            form: modal,

            fields: {
                name: new Input(modal.find('[name="name"]')),
                websiteId: new Input(modal.find('[name="website_id"]')),
            },
        });

        this.form.on('submit', () => {
            // noinspection JSIgnoredPromiseFromCall
            this.submit();
        });

        this.closeButton = new Button(modal.find('.btn-close'));
        this.submitButton = new Button(modal.find('.btn-submit'));
    }

    public create(): void {
        this.modal.show();
    }

    private async submit(): Promise<void> {
        this.changeState('submitting');
        this.form.clearErrors();

        try {
            await TagsFacade.create(
                <any>this.form.serialize(),
            );

            this.bus.emit('tag::created');
            this.modal.hide();

            await swal({
                title: 'Success',
                text: 'Tag has been created.',
                icon: 'success',
            });
        } catch (error) {
            this.form.processErrors(error);
        }

        this.changeState('ready');
    }

    private changeState(state: string): void {
        switch (state) {
            case 'submitting':
                this.closeButton.disable();

                this.submitButton.disable();
                this.submitButton.showSpinner();

                break;

            case 'ready':
                this.closeButton.enable();

                this.submitButton.enable();
                this.submitButton.hideSpinner();

                break;
        }
    }

}
