import Client from '../Client';

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

        return Client.executeProgressable({
            method: 'post',
            url: '/attachments',
            data,
        });
    }

}
