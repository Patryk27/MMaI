import { ApiClient } from '../ApiClient';
import { Tag } from './Tag';

// @todo create "UpdateTagRequest" model
export class TagsFacade {

    public static create(tag: Tag): Promise<void> {
        return ApiClient.request({
            method: 'post',
            url: '/api/tags',
            data: tag,
        });
    }

    public static update(id: number, tag: any): Promise<void> {
        return ApiClient.request({
            method: 'put',
            url: `/api/tags/${id}`,
            data: tag,
        });
    }

    public static delete(id: number): Promise<void> {
        return ApiClient.request({
            method: 'delete',
            url: `/api/tags/${id}`,
        });
    }

}
