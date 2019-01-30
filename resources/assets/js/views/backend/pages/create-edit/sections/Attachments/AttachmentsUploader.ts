import { AttachmentsFacade } from '../../../../../../api/attachments/AttachmentsFacade';
import { EventBus } from '../../../../../../utils/EventBus';
import { AttachmentsTable } from './AttachmentsTable';

export class AttachmentsUploader {

    constructor(
        private readonly bus: EventBus,
        private readonly table: AttachmentsTable,
    ) {
    }

    public async pickAndUpload(): Promise<void> {
        const attachmentFile = await this.pickFile();

        const attachment = this.table.add({
            // We must have a way to identify this temporary attachment until it gets a proper id from the backend.
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

        const uploader = AttachmentsFacade.create(attachmentFile);

        uploader.onProgress(({ uploadedPercentage }) => {
            attachment.statusPayload.uploadedPercentage = uploadedPercentage;

            this.table.update(attachment);
        });

        uploader.onSuccess((uploadedAttachment) => {
            this.table.remove(attachment.id);
            this.table.add(uploadedAttachment);

            this.bus.emit('form::invalidate');
        });

        uploader.onFailure((error) => {
            this.table.remove(attachment.id);

            // noinspection JSIgnoredPromiseFromCall
            swal({
                title: 'Failed to upload file',
                text: error.getMessage(),
                icon: 'error',
            });
        });
    }

    /**
     * Opens a file selection modal allowing user to pick an attachment to upload.
     *
     * Resolves returned Promise when user has picked a file.
     * Rejects returned Promise when user has picked no file (e.g. by rejecting the file selection modal).
     */
    private pickFile(): Promise<File> {
        return new Promise((resolve, reject) => {
            const form = $('<form>');

            $('<input>')
                .attr('type', 'file')
                .appendTo(form);

            const fileInput = form.find('input');

            form.on('submit', () => {
                const files = fileInput.prop('files');

                if (files.length === 0) {
                    reject();
                } else {
                    resolve(files[0]);
                }
            });

            fileInput.on('change', () => {
                form.submit();
            });

            fileInput.click();
        });
    }

}
