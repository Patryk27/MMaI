import { ApiConnector } from '@/modules/core/ApiConnector';

export class PagesFacade {

    public static create(page: object): Promise<{ redirectTo: string }> {
        return ApiConnector.request({
            method: 'post',
            url: '/api/pages',
            data: page,
        });
    }

    public static update(id: number, page: object): Promise<{ redirectTo: string }> {
        return ApiConnector.request({
            method: 'put',
            url: '/api/pages/' + id,
            data: page,
        });
    }

}
