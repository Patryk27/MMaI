import AttachmentsTable from './Attachments/AttachmentsTable';
import AttachmentsUploader from './Attachments/AttachmentsUploader';
import swal from 'sweetalert';

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
            this.$uploadAttachment();
        });

        // Initialize the attachments' table
        this.$table = new AttachmentsTable(this.$dom.attachmentsTable);

        this.$table.onAction('show', (attachment) => {
            this.$showAttachment(attachment);
        });

        this.$table.onAction('delete', (attachment) => {
            // noinspection JSIgnoredPromiseFromCall
            this.$deleteAttachment(attachment);
        });

        // Initialize the attachments' uploader
        this.$uploader = new AttachmentsUploader();
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

    /**
     * @private
     *
     * @returns {Promise<void>}
     */
    async $uploadAttachment() {
        const attachmentFile = await this.$uploader.selectAttachment();

        const attachment = this.$table.add({
            // We have to identify this temporary attachment somehow - random number will do just fine
            id: Math.random(),

            // For the name and size, let's just take values out of the given {@see File} struct
            name: attachmentFile.name,
            size: `${attachmentFile.size} bytes`,

            // Let's mark this attachment as "being uploaded", so that is has a proper loading progress bar
            status: 'being-uploaded',

            statusPayload: {
                uploadedPercentage: 0,
            },
        });

        // Initialize the uploader
        const uploader = this.$uploader.uploadAttachment(attachmentFile);

        uploader.onSuccess(({ data }) => {
            this.$table.remove(attachment.id);
            this.$table.add(data);
        });

        uploader.onFailure(({ message }) => {
            this.$table.remove(attachment.id);

            // noinspection JSIgnoredPromiseFromCall
            swal({
                title: 'Failed to upload file',
                text: message,
                icon: 'error',
            });
        });

        uploader.onProgress(({ uploadedPercentage }) => {
            attachment.statusPayload.uploadedPercentage = uploadedPercentage;

            this.$table.update(attachment);
        });
    }

    /**
     * @private
     *
     * @param {object} attachment
     */
    $showAttachment(attachment) {
        window.open(`/attachments/${attachment.id}/download`);
    }

    /**
     * @private
     *
     * @param {object} attachment
     * @returns {Promise<void>}
     */
    async $deleteAttachment(attachment) {
        const result = await swal({
            title: 'Are you sure?',
            text: `Do you want to delete attachment [${attachment.name}]?`,
            icon: 'warning',
            dangerMode: true,

            buttons: {
                cancel: 'Cancel',

                confirm: {
                    text: 'Delete',
                    closeModal: false,
                },
            },
        });

        if (result) {
            this.$table.remove(attachment.id);
        }

        swal.close();
    }

}
