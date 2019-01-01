import { Clipboard } from '../../../../../utils/Clipboard';
import { EventBus } from '../../../../../utils/EventBus';
import { Tippy } from '../../../../../utils/Tippy';
import { AttachmentsDeleter } from './Attachments/AttachmentsDeleter';
import { AttachmentsTable } from './Attachments/AttachmentsTable';
import { AttachmentsUploader } from './Attachments/AttachmentsUploader';

export class AttachmentsSection {

    private readonly dom: {
        addAttachmentButton: JQuery,
        attachmentsTable: JQuery,
    };

    private readonly table: AttachmentsTable;
    private readonly deleter: AttachmentsDeleter;
    private readonly uploader: AttachmentsUploader;

    constructor(bus: EventBus, container: JQuery) {
        this.dom = {
            addAttachmentButton: container.find('#add-attachment-button'),
            attachmentsTable: container.find('#attachments-table'),
        };

        this.dom.addAttachmentButton.on('click', () => this.uploader.pickAndUpload());

        this.table = new AttachmentsTable(this.dom.attachmentsTable);

        this.table.onRowAction('copy-url', async ({ attachment, target }) => {
            await Clipboard.writeText(attachment.url);

            Tippy.once(target, {
                animation: 'shift-away',
                content: 'Attachment\'s URL has been copied.',
                placement: 'bottom',
            });
        });

        this.table.onRowAction('delete', ({ attachment }) => this.deleter.delete(attachment));

        this.deleter = new AttachmentsDeleter(bus, this.table);
        this.uploader = new AttachmentsUploader(bus, this.table);
    }

    public serialize(): Array<number> {
        let attachmentsIds: Array<number> = [];

        this.table.getAllSelector().each((_, row) => {
            attachmentsIds.push(
                $(row).data('attachment').id,
            );
        });

        return attachmentsIds;
    }

}
