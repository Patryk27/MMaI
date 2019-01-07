import { ApiClient } from '../ApiClient';
import { Tag } from './Tag';

// @todo create "UpdateTagRequest" model
// @todo create "Query" model
export class TagsFacade {

    public static async search(query: any): Promise<Array<Tag>> {
        const response: any = await ApiClient.request({
            method: 'get',
            url: '/api/tags',
            params: {
                query: JSON.stringify(query),
            },
        });

        return response.items.map((tag: any) => {
            return new Tag(tag);
        });
    }

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
