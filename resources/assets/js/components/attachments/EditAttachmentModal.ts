import { Attachment } from '@/api/attachments/Attachment';
import { EditAttachmentForm } from '@/components/attachments/EditAttachmentForm';
import { GenericModalForm } from '@/components/core/GenericModalForm';

export class EditAttachmentModal {

    private modal: GenericModalForm<Attachment, EditAttachmentForm>;

    public constructor(modal: JQuery) {
        this.modal = new GenericModalForm(modal, new EditAttachmentForm(modal));
    }

    public show(attachment: Attachment): Promise<Attachment | null> {
        return this.modal.show(attachment);
    }

}
