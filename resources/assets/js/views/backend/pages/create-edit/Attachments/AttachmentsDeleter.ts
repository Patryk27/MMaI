import { Attachment } from '@/api/attachments/Attachment';
import { EventBus } from '@/utils/EventBus';
import swal from 'sweetalert';
import { AttachmentsTable } from './AttachmentsTable';

export class AttachmentsDeleter {

    constructor(
        private readonly bus: EventBus,
        private readonly table: AttachmentsTable,
    ) {

    }

    async delete(attachment: Attachment): Promise<void> {
        const result = await swal({
            title: 'Removing attachment',
            text: `Do you want to remove attachment [${attachment.name}]?`,
            icon: 'warning',
            dangerMode: true,
            buttons: {
                cancel: true,
                confirm: {
                    text: 'Delete',
                    closeModal: false,
                },
            },
        });

        swal.close();

        if (result) {
            this.table.remove(attachment.id);
            this.bus.emit('form::invalidate');

            // noinspection JSIgnoredPromiseFromCall
            swal({
                title: 'Success',
                text: 'Attachment has been removed.',
                icon: 'success',
            });
        }
    }

}
