import { ApiClient } from '@/modules/core/ApiClient';

export class PagesFacade {

    public static createPage(page: object): Promise<{ redirectTo: string }> {
        return ApiClient.request({
            method: 'post',
            url: 'pages',
            data: page,
        });
    }

    public static updatePage(id: number, page: object): Promise<{ redirectTo: string }> {
        return ApiClient.request({
            method: 'put',
            url: 'pages/' + id,
            data: page,
        });
    }

}
