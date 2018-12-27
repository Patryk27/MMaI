export default class Error {

    /**
     * @param {string} type
     * @param {string} message
     * @param {*} payload
     */
    constructor(type, message, payload = null) {
        this.$type = type;
        this.$message = message;
        this.$payload = payload;
    }

    /**
     * @returns {string}
     */
    getType() {
        return this.$type;
    }

    /**
     * @returns {string}
     */
    getMessage() {
        return this.$message;
    }

    /**
     * @returns {*}
     */
    getPayload() {
        return this.$payload;
    }

    /**
     * @returns {string}
     */
    toString() {
        return this.$message;
    }

}
