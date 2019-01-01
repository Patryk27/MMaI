import { EventBus } from '../../../../utils/EventBus';

export class TagsFilters {

    private bus: EventBus;

    private dom: {
        form: JQuery,
        websiteIds: JQuery,
    };

    constructor(bus: EventBus, form: JQuery) {
        this.bus = bus;

        this.dom = {
            form,
            websiteIds: form.find('[name="website_ids[]"]'),
        };

        this.dom.form.on('change', 'select', () => {
            this.submit();
        });
    }

    public submit(): void {
        this.bus.emit('filters::submitted', {
            websiteIds: {
                operator: 'in',
                value: this.dom.websiteIds.val(),
            },
        });
    }

}
