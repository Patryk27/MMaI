import { ApiClient } from '../ApiClient';
import { ApiTrackedResponse } from '../ApiTrackedResponse';
import { Attachment } from './Attachment';

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

}
