export default class CreateTagModalComponent {

    /**
     * @param {string} modalSelector
     */
    constructor(modalSelector) {
        const $modal = $(modalSelector);

        this.$dom = {
            modal: $modal,

            form: {
                name: $modal.find('[name="name"]'),
                languageId: $modal.find('[name="languageID"]'),
            },
        };

        this.$dom.modal.on('shown.bs.modal', () => {
            this.$dom.form.name.focus();
        });
    }

    /**
     * @param {number} selectedLanguageId
     */
    show(selectedLanguageId) {
        this.$dom.form.languageId.val(selectedLanguageId);
        this.$dom.modal.modal();
    }

}