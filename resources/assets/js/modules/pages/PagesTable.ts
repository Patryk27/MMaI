import { Table } from '@/ui/components/Table';

interface Configuration {
    dom: {
        loader: JQuery,
        table: JQuery,
    },
}

export class PagesTable {

    private readonly table: Table;
    private filters: object = {};

    constructor(config: Configuration) {
        this.table = new Table({
            loaderSelector: config.dom.loader,
            tableSelector: config.dom.table,

            autofocus: true,
            source: '/api/pages',

            onPrepareRequest(request) {
                return Object.assign(request, {
                    filters: this.filters,
                });
            },
        });
    }

    public refresh(filters: object): void {
        this.filters = filters;
        this.table.refresh();
    }

}
