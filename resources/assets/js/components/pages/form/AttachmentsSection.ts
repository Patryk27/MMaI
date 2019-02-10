import { AttachmentsTable } from '@/components/attachments/AttachmentsTable';
import { EditAttachmentModal } from '@/components/attachments/EditAttachmentModal';
import { RemoveAttachmentModal } from '@/components/attachments/RemoveAttachmentModal';
import { UploadAttachmentModal } from '@/components/attachments/UploadAttachmentModal';
import { FormControl, FormError } from '@/ui/form';
import { EventBus } from '@/utils/EventBus';

export class AttachmentsSection implements FormControl {

    public readonly id: string = 'attachments';

    private readonly table: AttachmentsTable;
    private readonly uploadModal: UploadAttachmentModal;
    private readonly editModal: EditAttachmentModal;
    private readonly removeModal: RemoveAttachmentModal;

    constructor(bus: EventBus) {
        this.table = new AttachmentsTable($('#attachments-table'));
        this.uploadModal = new UploadAttachmentModal($('#upload-attachment-modal'));
        this.editModal = new EditAttachmentModal($('#edit-attachment-modal'));
        this.removeModal = new RemoveAttachmentModal();

        this.table.onAttachment('download', ({ attachment }) => {
            alert('download: ' + attachment.url);
        });

        this.table.onAttachment('edit', ({ attachment }) => {
            alert('edit: ' + attachment.url);
        });

        this.table.onAttachment('remove', ({ attachment }) => {
            this.removeModal
                .remove(attachment)
                .then((deleted) => {
                    if (deleted) {
                        bus.emit('attachment::removed', attachment);
                    }
                })
                .catch(window.onerror);
        });

        bus.on('attachment::uploaded', (attachment) => {
            this.table.add(attachment);
        });

        bus.on('attachment::removed', (attachment) => {
            this.table.remove(attachment);
        });

        $('#upload-attachment-button').on('click', () => {
            this.uploadModal
                .show()
                .then((attachment) => {
                    if (attachment) {
                        bus.emit('attachment::uploaded', attachment);
                    }
                })
                .catch(window.onerror);
        });
    }

    public serialize(): any {
        let attachmentIds: Array<number> = [];

        this.table.getAll().each((_, row) => {
            attachmentIds.push(
                $(row).data('attachment').id,
            );
        });

        return { attachment_ids: attachmentIds };
    }

    public focus(): void {
        // Nottin' here
    }

    public addError(error: FormError): void {
        // Nottin' here
    }

    public clearErrors(): void {
        // Nottin' here
    }

}
