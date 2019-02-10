import { Select } from '@/ui/components';
import { Form, FormInputControl } from '@/ui/form';

interface Configuration {
    dom: {
        container: JQuery,
    },

    events: {
        onChange: () => void,
    },
}

export class TagsFilters {

    private readonly form: Form;

    public constructor(config: Configuration) {
        this.form = new Form({
            controls: [
                new FormInputControl('website_ids', Select.fromContainer(config.dom.container, 'website_ids[]')),
            ],
        });

        config.dom.container.on('change', () => {
            config.events.onChange();
        });
    }

    public get get(): object {
        const filters = this.form.serialize();

        return {
            websiteId: {
                operator: 'in',
                value: filters.website_ids,
            },
        };
    }

}
