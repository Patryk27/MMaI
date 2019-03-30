import { Attachment } from '@/modules/attachments/Attachment';
import { ApiClient } from '@/modules/core/ApiClient';
import { ApiTrackedResponse } from '@/modules/core/ApiTrackedResponse';

export class AttachmentsFacade {

    public static create(file: File): ApiTrackedResponse<Attachment> {
        const data = new FormData();
        data.append('attachment', file);

        return ApiClient.trackedRequest({
            method: 'post',
            url: '/api/attachments',
            data,
        });
    }

    public static async update(attachment: Attachment): Promise<Attachment> {
        return new Attachment(await ApiClient.request({
            method: 'put',
            url: '/api/attachments/' + attachment.id,
            data: attachment,
        }));
    }

}
