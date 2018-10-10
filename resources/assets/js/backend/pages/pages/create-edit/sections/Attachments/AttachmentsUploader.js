import AttachmentsFacade from '../../../../../../base/api/AttachmentsFacade';
import swal from 'sweetalert';

export default class AttachmentsUploader {

    /**
     * @param {AttachmentsTable} table
     */
    constructor(table) {
        this.$table = table;
    }

    /**
     * Opens the file selection modal and uploads given attachment, if any has been selected.
     *
     * @returns {Promise<void>}
     */
    async pickAndUpload() {
        const attachmentFile = await this.$pickFile();

        const attachment = this.$table.add({
            // We must have a way to identify this temporary attachment until it gets a proper id from the backend;
            // A pseudo-random number will do just fine
            id: Math.random(),

            // For the name, mime and size let's just take values out of the given {@see File} structure
            name: attachmentFile.name,
            mime: attachmentFile.type,
            size: `${attachmentFile.size} bytes`,

            // We do not have any URL yet, so let's just use an empty link for now
            url: '#',

            // Let's mark this attachment as "being uploaded", so that is has a proper loading progress bar
            status: 'being-uploaded',

            statusPayload: {
                uploadedPercentage: 0,
            },
        });

        // Initialize the uploader
        const uploader = AttachmentsFacade.create(attachmentFile);

        uploader.onProgress(({ uploadedPercentage }) => {
            attachment.statusPayload.uploadedPercentage = uploadedPercentage;

            this.$table.update(attachment);
        });

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
    }

    /**
     * @private
     *
     * Opens a file selection modal allowing user to pick an attachment to upload.
     *
     * Resolves returned Promise when user has picked a file.
     * Rejects returned Promise when user has picked no file (e.g. by rejecting the file selection modal).
     *
     * @returns {Promise<File>}
     */
    $pickFile() {
        return new Promise((resolve, reject) => {
            const $form = this.$buildForm();
            const $fileInput = $form.find('input');

            $form.on('submit', () => {
                const files = $fileInput.prop('files');

                if (files.length === 0) {
                    reject();
                } else {
                    resolve(files[0]);
                }
            });

            $fileInput.on('change', () => {
                $form.submit();
            });

            $fileInput.click();
        });
    }

    /**
     * @private
     *
     * @returns {jQuery}
     */
    $buildForm() {
        const $form = $('<form>');

        $('<input>')
            .attr('type', 'file')
            .appendTo($form);

        return $form;
    }

}
