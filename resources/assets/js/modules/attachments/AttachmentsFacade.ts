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

}
