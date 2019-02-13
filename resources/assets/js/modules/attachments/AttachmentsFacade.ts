import { Attachment } from '@/modules/attachments/Attachment';
import { ApiConnector } from '@/modules/core/ApiConnector';
import { ApiTrackedResponse } from '@/modules/core/ApiTrackedResponse';

export class AttachmentsFacade {

    public static create(file: File): ApiTrackedResponse<Attachment> {
        const data = new FormData();
        data.append('attachment', file);

        return ApiConnector.trackedRequest({
            method: 'post',
            url: '/api/attachments',
            data,
        });
    }

    public static async update(attachment: Attachment): Promise<Attachment> {
        return new Attachment(await ApiConnector.request({
            method: 'put',
            url: '/api/attachments/' + attachment.id,
            data: attachment,
        }));
    }

}
