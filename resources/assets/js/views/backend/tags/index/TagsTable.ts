import { InteractiveTable } from '../../../../components/InteractiveTable';
import { EventBus } from '../../../../utils/EventBus';

export class TagsTable {

    private dataTable: InteractiveTable;

    private state: {
        filters: any,
    };

    constructor(bus: EventBus, loader: JQuery, table: JQuery) {
        this.dataTable = new InteractiveTable({
            autofocus: true,
            loaderSelector: loader,
            source: '/api/tags',
            tableSelector: table,

            onPrepareRequest: (request) => {
                return Object.assign(request, {
                    filters: this.state.filters,
                });
            },
        });

        this.dataTable.onColumn('name', 'dblclick', function () {
            bus.emit('tag::edit', {
                tag: $(this).data('tag'),
            });
        });

        this.state = {
            filters: {},
        };
    }

    public refresh(filters: any): void {
        this.state.filters = filters;
        this.dataTable.refresh();
    }

}
