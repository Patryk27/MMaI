import { Form, InteractiveTable, Select } from '../../../../ui/components';
import { EventBus } from '../../../../utils/EventBus';

export class TagsTable {

    private table: InteractiveTable;
    private filters: Form;

    constructor(bus: EventBus, filters: JQuery, loader: JQuery, table: JQuery) {
        this.table = new InteractiveTable({
            autofocus: true,
            loaderSelector: loader,
            source: '/api/tags',
            tableSelector: table,

            onPrepareRequest: (request) => {
                return Object.assign(request, {
                    filters: this.getFilters(),
                });
            },
        });

        this.table.onColumn('name', 'click', function () {
            bus.emit('tag::edit', {
                tag: $(this).data('tag'),
            });
        });

        this.filters = new Form({
            form: filters,

            fields: {
                websiteIds: new Select(filters.find('[name="website_ids[]"]')),
            },
        });

        this.filters.on('change', () => {
            this.refresh();
        });
    }

    public refresh(): void {
        this.table.refresh();
    }

    private getFilters(): any {
        return {
            websiteId: {
                operator: 'in',
                value: this.filters.find('websiteIds').getValue(),
            },
        };
    }

}
