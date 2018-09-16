import Requester from './Requester';

export default class TagsFacade {

    /**
     * Creates a new brand-new tag from given data.
     *
     * Passed object should have following structure:
     *   {
     *       name: string,
     *       language_id: number,
     *   }
     *
     * @param {object} tag
     * @returns {Promise<void>}
     */
    static async create(tag) {
        return Requester.execute({
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
        return Requester.execute({
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
        return Requester.execute({
            method: 'delete',
            url: `/tags/${id}`,
        });
    }

}
