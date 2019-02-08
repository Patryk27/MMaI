import { Select, Table } from '@/ui/components';
import { Form } from '@/ui/form';
import { FormInput } from '@/ui/form/FormInput';
import { EventBus } from '@/utils/EventBus';

export class TagsTable {

    private table: Table;
    private filters: Form;

    constructor(bus: EventBus, filters: JQuery, loader: JQuery, table: JQuery) {
        this.table = new Table({
            autofocus: true,
            loaderSelector: loader,
            source: '/api/tags',
            tableSelector: table,

            onPrepareRequest: (request) => {
                return Object.assign(request, {
                    filters: this.buildFilters(),
                });
            },
        });

        this.table.onColumn('name', 'click', function () {
            bus.emit('tag::edit', {
                tag: $(this).data('tag'),
            });
        });

        this.filters = new Form({
            controls: [
                new FormInput('website_ids', Select.fromContainer(filters, 'website_ids[]')),
            ],
        });

        filters.on('change', () => {
            this.refresh();
        });
    }

    public refresh(): void {
        this.table.refresh();
    }

    private buildFilters(): any {
        const filters = this.filters.serialize();

        return {
            websiteId: {
                operator: 'in',
                value: filters.website_ids,
            },
        };
    }

}
