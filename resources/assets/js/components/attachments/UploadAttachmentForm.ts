import { AttachmentsFacade } from '@/api/attachments/AttachmentsFacade';
import { EventBus } from '@/utils/EventBus';
import swal from 'sweetalert';
import { AttachmentsTable } from './AttachmentsTable';

export class UploadAttachmentForm {

    constructor(
        private readonly bus: EventBus,
        private readonly table: AttachmentsTable,
    ) {
    }

    public async upload(file: File): Promise<void> {
        const attachment = this.table.add({
            // We must have a way to identify this temporary attachment until it gets a proper id from the backend; a
            // pseudo-random number will do just fine
            id: Math.random(),

            // For the name, mime and size let's just take values out of the given {@see File} structure
            name: file.name,
            mime: file.type,
            size: `${file.size} bytes`,

            // We do not have any URL yet, so let's just use an empty link for now
            url: '#',

            // Let's mark this attachment as "being uploaded", so that is has a proper loading progress bar
            status: 'being-uploaded',

            statusPayload: {
                uploadedPercentage: 0,
            },
        });

        const uploader = AttachmentsFacade.create(file);

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
                title: 'Failed to upload the attachment',
                text: error.message,
                icon: 'error',
            });
        });
    }

}
