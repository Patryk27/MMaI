import { ApiClient } from '../ApiClient';
import { Tag } from './Tag';

// @todo create "UpdateTagRequest" model
export class TagsFacade {

    public static async search(query: object): Promise<Array<Tag>> {
        // @todo create the "Query" model

        const tags = await ApiClient.request<Array<any>>({
            method: 'get',
            url: '/api/tags',
            params: {
                query: JSON.stringify(query),
            },
        });

        return tags.map((tag: any) => new Tag(tag));
    }

    public static create(tag: Tag): Promise<void> {
        return ApiClient.request({
            method: 'post',
            url: '/tags',
            data: tag,
        });
    }

    public static update(id: number, tag: any): Promise<void> {
        return ApiClient.request({
            method: 'put',
            url: `/tags/${id}`,
            data: tag,
        });
    }

    public static delete(id: number): Promise<void> {
        return ApiClient.request({
            method: 'delete',
            url: `/tags/${id}`,
        });
    }

}
