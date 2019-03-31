import { Attachment } from '@/modules/attachments/Attachment';
import { EditAttachmentForm } from '@/modules/attachments/EditAttachmentForm';
import { FormModal } from '@/modules/core/FormModal';

export class EditAttachmentModal {

    private modal: FormModal<Attachment, EditAttachmentForm>;

    public constructor(modal: JQuery) {
        this.modal = new FormModal(modal, new EditAttachmentForm(modal));
    }

    public show(attachment: Attachment): Promise<Attachment | null> {
        return this.modal.show(attachment);
    }

}
