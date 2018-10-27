import ApiRequester from './ApiRequester';

export default class AttachmentsFacade {

    /**
     * Uploads an attachment.
     *
     * @param {File} file
     * @returns {{onProgress: function, onSuccess: function, onFailure: function}}
     */
    static create(file) {
        const data = new FormData();

        data.append('attachment', file);

        return ApiRequester.executeProgressable({
            method: 'post',
            url: '/attachments',
            data,
        });
    }

}
