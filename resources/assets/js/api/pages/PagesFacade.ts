import { ApiClient } from '../ApiClient';

// @todo create "Page" model
export class PagesFacade {

    public static create(page: object): Promise<void> {
        return ApiClient.request({
            method: 'post',
            url: '/api/pages',
            data: page,
        });
    }

    public static update(id: number, page: object): Promise<void> {
        return ApiClient.request({
            method: 'put',
            url: '/api/pages/' + id,
            data: page,
        });
    }

}
