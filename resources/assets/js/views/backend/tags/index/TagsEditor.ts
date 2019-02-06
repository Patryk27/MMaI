import { Tag } from '@/api/tags/Tag';
import { TagsFacade } from '@/api/tags/TagsFacade';
import { Button, Field, Form, Input, Modal } from '@/ui/components';
import { EventBus } from '@/utils/EventBus';
import swal from 'sweetalert';

export class TagsEditor {

    private readonly modal: Modal;
    private readonly form: Form;
    private readonly closeButton: Button;
    private readonly submitButton: Button;

    private tag?: Tag;

    constructor(private readonly bus: EventBus, modal: JQuery) {
        this.modal = new Modal(modal);

        this.modal.onShown(() => {
            this.form.find('name').focus();
        });

        this.form = new Form({
            form: modal.find('form'),

            fields: [
                Field.input('name', modal),
            ],
        });

        this.form.on('submit', () => {
            // noinspection JSIgnoredPromiseFromCall
            this.submit();
        });

        this.closeButton = new Button(modal.find('.btn-close'));
        this.submitButton = new Button(modal.find('.btn-submit'));
    }

    public edit(tag: Tag): void {
        this.tag = tag;
        this.form.find('name').as<Input>().setValue(tag.name);
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
