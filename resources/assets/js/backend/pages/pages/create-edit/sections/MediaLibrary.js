/**
 * This class defines a section which contains the entire page's media library.
 */
export default class MediaLibrary {

    /**
     * @param {jQuery} container
     */
    constructor(container) {
        // @todo
    }

    serialize() {
        // @todo
    }

    /**
     * Fired before section is submitted.
     */
    handleBeforeSubmit() {
        this.$block(true);
    }

    /**
     * Fired after section has been submitted.
     */
    handleAfterSubmit() {
        this.$block(false);
    }

    $block(blocked) {
        // @todo
    }

}