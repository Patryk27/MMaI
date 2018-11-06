export default class TagsFilters {

    /**
     * @param {Bus} bus
     * @param {jQuery} $form
     */
    constructor(bus, $form) {
        this.$bus = bus;

        this.$dom = {
            form: $form,
            languageIds: $form.find('[name="language_ids[]"]'),
        };

        this.$dom.form.on('change', 'select', () => {
            this.submit();
        });
    }

    /**
     * Force-submits the form.
     */
    submit() {
        this.$bus.emit('filters::submitted', {
            languageIds: {
                operator: 'in',
                value: this.$dom.languageIds.val(),
            },
        });
    }

}
