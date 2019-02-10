import { Attachment } from '@/api/attachments/Attachment';
import swal from 'sweetalert';

export class RemoveAttachmentModal {

    async remove(attachment: Attachment): Promise<boolean> {
        return await swal({
            title: 'Removing attachment',
            text: `Do you want to remove attachment [${attachment.name}]?`,
            icon: 'warning',
            dangerMode: true,
            buttons: {
                cancel: true,
                confirm: true,
            },
        });
    }

}
