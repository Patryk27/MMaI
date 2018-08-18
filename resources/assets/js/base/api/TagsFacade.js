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
     * @param {object} tagData
     * @returns {Promise<void>}
     */
    static async create(tagData) {
        return Requester.execute('post', '/backend/tags', tagData);
    }

    /**
     * Updates an already existing tag.
     *
     * Passed object should have following structure:
     *   {
     *       name: string,
     *   }
     *
     * @param {number} tagId
     * @param {object} tagData
     * @returns {Promise<void>}
     */
    static async update(tagId, tagData) {
        return Requester.execute('put', `/backend/tags/${tagId}`, tagData);
    }

    /**
     * Removes tag with specified id.
     *
     * @param {number} tagId
     * @returns {Promise<void>}
     */
    static async delete(tagId) {
        return Requester.execute('delete', `/backend/tags/${tagId}`);
    }

}