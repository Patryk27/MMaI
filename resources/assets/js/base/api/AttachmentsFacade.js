import Requester from './Requester';

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

        return Requester.executeProgressable({
            method: 'post',
            url: '/attachments',
            data,
        });
    }

}
