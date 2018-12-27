export default class TagsFilters {

    /**
     * @param {EventBus} bus
     * @param {jQuery} $form
     */
    constructor(bus, $form) {
        this.$bus = bus;

        this.$dom = {
            form: $form,
            websiteIds: $form.find('[name="website_ids[]"]'),
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
            websiteIds: {
                operator: 'in',
                value: this.$dom.websiteIds.val(),
            },
        });
    }

}
