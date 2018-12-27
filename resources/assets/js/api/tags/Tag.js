export default class Tag {

    /**
     * @param {object} data
     */
    constructor(data) {
        this.id = data.id;
        this.websiteId = data.website_id;
        this.name = data.name;
        this.createdAt = data.created_at;
        this.updatedAt = data.updated_at;
    }

}
