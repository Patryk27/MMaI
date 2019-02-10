import { AttachmentsTable } from '@/components/attachments/AttachmentsTable';
import { DeleteAttachmentModal } from '@/components/attachments/DeleteAttachmentModal';
import { EditAttachmentModal } from '@/components/attachments/EditAttachmentModal';
import { UploadAttachmentForm } from '@/components/attachments/UploadAttachmentForm';
import { FilePicker } from '@/ui/components';
import { FormControl, FormError } from '@/ui/form';
import { EventBus } from '@/utils/EventBus';

export class AttachmentsSection implements FormControl {

    public readonly id: string = 'attachments';

    private readonly table: AttachmentsTable;
    private readonly deleteModal: DeleteAttachmentModal;
    private readonly editModal: EditAttachmentModal;
    private readonly uploadForm: UploadAttachmentForm;

    constructor(bus: EventBus) {
        this.table = new AttachmentsTable($('#attachments-table'));
        this.deleteModal = new DeleteAttachmentModal(bus, this.table);
        this.editModal = new EditAttachmentModal($('#edit-attachments-modal'));
        this.uploadForm = new UploadAttachmentForm(bus, this.table);

        this.table.onAttachment('download', ({ attachment }) => {
            alert('download: ' + attachment.url);
        });

        this.table.onAttachment('edit', ({ attachment }) => {
            alert('edit: ' + attachment.url);
            // this.editModal.show(attachment);
        });

        this.table.onAttachment('delete', ({ attachment }) => {
            this.deleteModal.delete(attachment).catch(window.onerror);
        });

        $('#upload-attachment-button').on('click', async () => {
            const file = await new FilePicker().run();

            await this.uploadForm.upload(file);
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
