import AttachmentsFacade from '../../../../../../base/api/AttachmentsFacade';

export default class AttachmentsUploader {

    /**
     * Opens a file selection modal allowing user to select an attachment to upload.
     *
     * Resolves returned Promise when user has picked a file.
     * Rejects returned Promise when user has picked no file.
     *
     * @returns {Promise<File>}
     */
    selectAttachment() {
        return new Promise((resolve, reject) => {
            const $form = this.$buildForm();
            const $fileInput = $form.find('input');

            $form.on('submit', () => {
                const files = $fileInput.prop('files');

                if (files.length === 0) {
                    reject();
                } else {
                    resolve(files[0]);
                }
            });

            $fileInput.on('change', () => {
                $form.submit();
            });

            $fileInput.click();
        });
    }

    /**
     * Uploads given attachment.
     *
     * @see AttachmentsFacade.create
     *
     * @param {File} attachmentFile
     * @returns {{onSuccess: function, onFailure: function, onProgress: function}}
     */
    uploadAttachment(attachmentFile) {
        return AttachmentsFacade.create(attachmentFile);
    }

    /**
     * @returns {jQuery}
     */
    $buildForm() {
        const $form = $('<form>');

        $('<input>')
            .attr('type', 'file')
            .appendTo($form);

        return $form;
    }

}
