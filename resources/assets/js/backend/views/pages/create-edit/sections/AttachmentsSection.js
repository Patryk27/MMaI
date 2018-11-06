import AttachmentsDeleter from './Attachments/AttachmentsDeleter';
import AttachmentsTable from './Attachments/AttachmentsTable';
import AttachmentsUploader from './Attachments/AttachmentsUploader';
import Clipboard from '../../../../../utils/Clipboard';
import Tippy from '../../../../../utils/Tippy';

export default class AttachmentsSection {

    /**
     * @param {Bus} bus
     * @param {jQuery} $container
     */
    constructor(bus, $container) {
        this.$dom = {
            addAttachmentButton: $container.find('#add-attachment-button'),
            attachmentsTable: $container.find('#attachments-table'),
        };

        // Bind handler to the "add attachment" button
        this.$dom.addAttachmentButton.on('click', () => {
            // noinspection JSIgnoredPromiseFromCall
            this.$uploader.pickAndUpload();
        });

        // Initialize the attachments' table
        this.$table = new AttachmentsTable(this.$dom.attachmentsTable);

        this.$table.onRowAction('copy-url', async ({ attachment, target }) => {
            await Clipboard.writeText(attachment.url);

            Tippy.once(target, {
                animation: 'shift-away',
                content: 'Attachment\'s URL has been copied!',
                placement: 'bottom',
            });
        });

        this.$table.onRowAction('delete', ({ attachment }) => {
            // noinspection JSIgnoredPromiseFromCall
            this.$deleter.delete(attachment);
        });

        // Initialize the attachments' uploader & deleter
        this.$uploader = new AttachmentsUploader(bus, this.$table);
        this.$deleter = new AttachmentsDeleter(bus, this.$table);
    }

    /**
     * @returns {object}
     */
    serialize() {
        let attachmentsIds = [];

        this.$table.getAll().each((_, row) => {
            attachmentsIds.push(
                $(row).data('attachment').id,
            );
        });

        return attachmentsIds;
    }

}
