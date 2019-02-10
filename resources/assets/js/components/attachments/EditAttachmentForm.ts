import { Attachment } from '@/api/attachments/Attachment';
import { GenericForm } from '@/components/core/GenericForm';
import { Input } from '@/ui/components/Input';
import { Form } from '@/ui/form/Form';
import { FormInputControl } from '@/ui/form/FormInputControl';

export class EditAttachmentForm implements GenericForm<Attachment> {

    private form: Form;
    private attachment?: Attachment;

    public constructor(container: JQuery) {
        this.form = new Form({
            controls: [
                new FormInputControl('name', Input.fromContainer(container, 'name')),
            ],
        });
    }

    public reset(attachment: Attachment): void {
        this.form.find<FormInputControl>('name').input.value = attachment.name;
        this.attachment = attachment;
    }

    public async submit(): Promise<Attachment | null> {
        this.form.clearErrors();

        try {
            Object.assign(this.attachment, this.form.serialize());

            // return await AttachmentsFacade.update(this.attachment); @todo
        } catch (error) {
            if (error.formErrors) {
                this.form.addErrors(error.formErrors);
            } else {
                throw error;
            }
        }

        return null;
    }

}