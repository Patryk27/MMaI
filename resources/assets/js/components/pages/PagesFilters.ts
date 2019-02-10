import { Input, Select } from '@/ui/components';
import { Form, FormInputControl, FormSelectControl } from '@/ui/form';

interface Configuration {
    dom: {
        container: JQuery,
    },

    events: {
        onChange: () => void,
    },
}

export class PagesFilters {

    private readonly form: Form;

    public constructor(config: Configuration) {
        this.form = new Form({
            controls: [
                new FormSelectControl('types', Select.fromContainer(config.dom.container, 'types[]')),
                new FormInputControl('url', Input.fromContainer(config.dom.container, 'url')),
                new FormSelectControl('websites', Select.fromContainer(config.dom.container, 'website_ids[]')),
                new FormSelectControl('status', Select.fromContainer(config.dom.container, 'statuses[]')),
            ],
        });

        config.dom.container.on('change', () => {
            config.events.onChange();
        });
    }

    public get get(): object {
        const filters = this.form.serialize();

        return {
            type: { operator: 'in', value: filters.types },
            routeUrl: { operator: 'expression', value: filters.url },
            websiteId: { operator: 'in', value: filters.websites },
            status: { operator: 'in', value: filters.statuses },
        };
    }

}
