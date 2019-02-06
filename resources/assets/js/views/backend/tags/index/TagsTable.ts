import { Field, Form, Select, Table } from '@/ui/components';
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

            fields: [
                Field.select('websiteIds', filters),
            ],
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
                value: this.filters.find('websiteIds').as<Select>().serialize(),
            },
        };
    }

}
