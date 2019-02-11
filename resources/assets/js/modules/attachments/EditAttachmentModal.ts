import { Attachment } from '@/modules/attachments/Attachment';
import { EditAttachmentForm } from '@/modules/attachments/EditAttachmentForm';
import { GenericModalForm } from '@/modules/core/GenericModalForm';

export class EditAttachmentModal {

    private modal: GenericModalForm<Attachment, EditAttachmentForm>;

    public constructor(modal: JQuery) {
        this.modal = new GenericModalForm(modal, new EditAttachmentForm(modal));
    }

    public show(attachment: Attachment): Promise<Attachment | null> {
        return this.modal.show(attachment);
    }

}
