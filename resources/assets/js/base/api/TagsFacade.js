import axios from 'axios';

export default class TagsFacade {

    /**
     * Creates a new brand-new tag from given data.
     *
     * @param {object} tagData
     * @returns {Promise<object>}
     */
    async create(tagData) {
        return await axios.post('/backend/tags/create', {tagData});
    }

}