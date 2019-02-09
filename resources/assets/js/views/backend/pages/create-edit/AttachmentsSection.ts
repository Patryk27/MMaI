import { FilePicker } from '@/ui/components';
import { FormControl, FormError } from '@/ui/form';
import { Clipboard } from '@/utils/Clipboard';
import { EventBus } from '@/utils/EventBus';
import { Tippy } from '@/utils/Tippy';
import { AttachmentsDeleter } from './Attachments/AttachmentsDeleter';
import { AttachmentsTable } from './Attachments/AttachmentsTable';
import { AttachmentsUploader } from './Attachments/AttachmentsUploader';

export class AttachmentsSection implements FormControl {

    public readonly id: string = 'attachments';

    private readonly table: AttachmentsTable;
    private readonly deleter: AttachmentsDeleter;
    private readonly uploader: AttachmentsUploader;

    constructor(bus: EventBus) {
        this.table = new AttachmentsTable($('#attachments-table'));
        this.deleter = new AttachmentsDeleter(bus, this.table);
        this.uploader = new AttachmentsUploader(bus, this.table);

        this.table.onRowAction('copy-url', async ({ attachment, target }) => {
            await Clipboard.writeText(attachment.url);

            Tippy.once(target, {
                animation: 'shift-away',
                content: 'Attachment\'s URL has been copied.',
                placement: 'bottom',
            });
        });

        this.table.onRowAction('delete', ({ attachment }) => {
            this.deleter.delete(attachment).catch(window.onerror);
        });

        $('#upload-attachment-button').on('click', async () => {
            const filePicker = new FilePicker();

            await this.uploader.upload(
                await filePicker.run(),
            );
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
