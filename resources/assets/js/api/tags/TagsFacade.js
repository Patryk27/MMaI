import Client from '../Client';
import Tag from './Tag';

export default class TagsFacade {

    /**
     * Returns all the tags matching given query.
     *
     * @param {object} query
     * @returns {Promise<Tag>}
     */
    static async search(query) {
        const response = await Client.execute({
            method: 'get',
            url: '/api/tags',
            params: {
                query: JSON.stringify(query),
            },
        });

        return response.data.map((tag) => new Tag(tag));
    }

    /**
     * Creates a new brand-new tag from given data.
     *
     * Passed object should have following structure:
     *   {
     *       name: string,
     *       website_id: number,
     *   }
     *
     * @param {object} tag
     * @returns {Promise<void>}
     */
    static async create(tag) {
        return Client.execute({
            method: 'post',
            url: '/tags',
            data: tag,
        });
    }

    /**
     * Updates an already existing tag.
     *
     * Passed object should have following structure:
     *   {
     *       name: string,
     *   }
     *
     * @param {number} id
     * @param {object} tag
     * @returns {Promise<void>}
     */
    static async update(id, tag) {
        return Client.execute({
            method: 'put',
            url: `/tags/${id}`,
            data: tag,
        });
    }

    /**
     * Removes tag with specified id.
     *
     * @param {number} id
     * @returns {Promise<void>}
     */
    static async delete(id) {
        return Client.execute({
            method: 'delete',
            url: `/tags/${id}`,
        });
    }

}
