import Client from '../Client';

export default class PagesFacade {

    /**
     * @param {object} page
     * @returns {Promise<void>}
     */
    static async create(page) {
        return Client.execute({
            method: 'post',
            url: '/api/pages',
            data: page,
        });
    }

    /**
     * @param {number} id
     * @param {object} page
     * @returns {Promise<void>}
     */
    static async update(id, page) {
        return Client.execute({
            method: 'put',
            url: '/api/pages/' + id,
            data: page,
        });
    }

}
