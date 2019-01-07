import swal from 'sweetalert';
import { Attachment } from '../../../../../../api/attachments/Attachment';
import { EventBus } from '../../../../../../utils/EventBus';
import { AttachmentsTable } from './AttachmentsTable';

export class AttachmentsDeleter {

    private readonly bus: EventBus;
    private readonly table: AttachmentsTable;

    constructor(bus: EventBus, attachmentsTable: AttachmentsTable) {
        this.bus = bus;
        this.table = attachmentsTable;
    }

    async delete(attachment: Attachment): Promise<void> {
        const result = await swal({
            title: 'Deleting attachment',
            text: `Do you want to delete attachment [${attachment.name}]?`,
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
                text: 'Attachment has been deleted.',
                icon: 'success',
            });
        }
    }

}
