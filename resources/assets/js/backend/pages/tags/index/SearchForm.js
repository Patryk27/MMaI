export default class SearchForm {

    /**
     * @param {Bus} bus
     * @param {jQuery} $form
     */
    constructor(bus, $form) {
        this.$bus = bus;

        this.$dom = {
            form: $form,
            languageId: $form.find('[name="language_id"]'),
        };

        this.$dom.form.on('change', 'select', () => {
            this.submit();
        });
    }

    /**
     * Force-submits the form.
     */
    submit() {
        this.$bus.emit('search-form::submit', {
            languageId: this.$dom.languageId.val(),
        });
    }

}