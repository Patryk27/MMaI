import { TagsFacade } from '@/api/tags/TagsFacade';
import { Button, Input, Modal, Select } from '@/ui/components';
import { Form } from '@/ui/form';
import { FormInput } from '@/ui/form/FormInput';
import { EventBus } from '@/utils/EventBus';

export class TagsCreator {

    private readonly modal: Modal;
    private readonly form: Form;
    private readonly closeButton: Button;
    private readonly submitButton: Button;

    constructor(private readonly bus: EventBus, modal: JQuery) {
        this.modal = new Modal(modal);
        this.modal.onShow(() => {
            const name = this.form.find<FormInput>('name');

            name.input.value = '';
            name.input.focus();
        });

        this.form = new Form({
            controls: [
                new FormInput('name', Input.fromContainer(modal, 'name')),
                new FormInput('website_id', Select.fromContainer(modal, 'website_id')),
            ],
        });

        this.closeButton = new Button(modal.find('.btn-close'));
        this.submitButton = new Button(modal.find('.btn-submit'));

        modal.on('submit', () => {
            this.submit().catch(window.onerror);
            return false;
        });
    }

    public run(): void {
        this.modal.show();
    }

    private async submit(): Promise<void> {
        this.changeState('submitting');
        this.form.clearErrors();

        try {
            await TagsFacade.create(this.form.serialize());

            this.bus.emit('tag::created');
            this.modal.hide();

            await swal({
                title: 'Success',
                text: 'Tag has been created.',
                icon: 'success',
            });
        } catch (error) {
            if (error.formErrors) {
                this.form.addErrors(error.formErrors);
            } else {
                throw error;
            }
        } finally {
            this.changeState('ready');
        }
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
