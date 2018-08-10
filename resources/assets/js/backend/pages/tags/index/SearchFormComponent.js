export default class SearchFormComponent {

    /**
     * @param {Bus} bus
     * @param {string} formSelector
     */
    constructor(bus, formSelector) {
        this.$bus = bus;

        const $form = $(formSelector);

        this.$dom = {
            form: $form,
            languageId: $form.find('[name="language_id"]'),
        };

        this.$dom.form.on('change', 'select', () => {
            this.$submit();
        });

        // In the next tick we're firing the "submit" event, so that the DataTable can refresh itself.
        setTimeout(() => {
            this.$submit();
        }, 0);
    }

    /**
     * @private
     */
    $submit() {
        this.$bus.emit('search-form::submit', {
            languageId: this.$dom.languageId.val(),
        });
    }

}