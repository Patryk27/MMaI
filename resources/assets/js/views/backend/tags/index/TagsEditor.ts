import { Tag } from '@/api/tags/Tag';
import { TagsFacade } from '@/api/tags/TagsFacade';
import { Button, Input, Modal } from '@/ui/components';
import { Form, FormInput } from '@/ui/form';
import { EventBus } from '@/utils/EventBus';

export class TagsEditor {

    private readonly modal: Modal;
    private readonly form: Form;
    private readonly closeButton: Button;
    private readonly submitButton: Button;

    private tag?: Tag;

    constructor(private readonly bus: EventBus, modal: JQuery) {
        this.modal = new Modal(modal);
        this.modal.onShow(() => {
            this.form.find('name').focus();
        });

        this.form = new Form({
            controls: [
                new FormInput('name', Input.fromContainer(modal, 'name')),
            ],
        });

        this.closeButton = new Button(modal.find('.btn-close'));
        this.submitButton = new Button(modal.find('.btn-submit'));

        modal.on('submit', () => {
            this.submit().catch(window.onerror);
            return false;
        });
    }

    public run(tag: Tag): void {
        this.form.find<FormInput>('name').input.value = tag.name;

        this.tag = tag;
        this.modal.show();
    }

    private async submit(): Promise<void> {
        this.changeState('submitting');
        this.form.clearErrors();

        try {
            await TagsFacade.update(this.tag.id, this.form.serialize());

            this.bus.emit('tag::updated');
            this.modal.hide();

            await swal({
                title: 'Success',
                text: 'Tag has been updated.',
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
