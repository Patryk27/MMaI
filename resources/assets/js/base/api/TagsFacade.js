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
        return await Requester.execute('post', '/backend/tags', tagData);
    }

    /**
     * Removes tag with specified id.
     *
     * @param {number} tagId
     * @returns {Promise<void>}
     */
    static async delete(tagId) {
        return await Requester.execute('delete', `/backend/tags/${tagId}`);
    }

}