import { Attachment } from '@/modules/attachments/Attachment';
import { AttachmentsFacade } from '@/modules/attachments/AttachmentsFacade';
import { Form } from '@/modules/core/Form';
import { Input } from '@/ui/components/Input';
import { Form as FormComponent, FormInputControl } from '@/ui/form';

export class EditAttachmentForm implements Form<Attachment> {

    private readonly form: FormComponent;
    private attachment?: Attachment;

    public constructor(container: JQuery) {
        this.form = new FormComponent({
            controls: [
                new FormInputControl('name', Input.fromContainer(container, 'name')),
            ],
        });
    }

    public reset(attachment: Attachment | null): void {
        if (!attachment) {
            throw 'No attachment has been specified.';
        }

        this.form.find<FormInputControl>('name').input.value = attachment.name;
        this.attachment = attachment;
    }

    public async submit(): Promise<Attachment | null> {
        this.form.clearErrors();

        try {
            Object.assign(this.attachment, this.form.serialize());

            return await AttachmentsFacade.update(this.attachment);
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
