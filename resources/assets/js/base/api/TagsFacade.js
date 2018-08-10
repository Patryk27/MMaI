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
     * This method returns a promise resolving to created tag's id.
     *
     * @param {object} tagData
     * @returns {Promise<number>}
     */
    static async create(tagData) {
        return await Requester.execute('post', '/backend/tags', {tagData});
    }

}