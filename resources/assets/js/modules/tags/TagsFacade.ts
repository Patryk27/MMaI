import { ApiConnector } from '@/modules/core/ApiConnector';
import { Tag } from '@/modules/tags/Tag';

export class TagsFacade {

    public static async search(query: any): Promise<Array<Tag>> {
        const response: any = await ApiConnector.request({
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

    public static create(tag: Tag): Promise<Tag> {
        return ApiConnector.request({
            method: 'post',
            url: '/api/tags',
            data: tag,
        });
    }

    public static update(tag: Tag): Promise<Tag> {
        return ApiConnector.request({
            method: 'put',
            url: `/api/tags/${tag.id}`,
            data: tag,
        });
    }

    public static delete(id: number): Promise<void> {
        return ApiConnector.request({
            method: 'delete',
            url: `/api/tags/${id}`,
        });
    }

}
