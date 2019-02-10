import { Tag } from '@/api/tags/Tag';
import { Table } from '@/ui/components';

interface Configuration {
    dom: {
        loader: JQuery,
        table: JQuery,
    },

    events: {
        onEdit: (tag: Tag) => void,
        onDelete: (el: JQuery, tag: Tag) => void,
    },
}

export class TagsTable {

    private readonly table: Table;
    private filters: object = {};

    constructor(config: Configuration) {
        this.table = new Table({
            loaderSelector: config.dom.loader,
            tableSelector: config.dom.table,

            autofocus: true,
            source: '/api/tags',

            onPrepareRequest: (request) => {
                return Object.assign(request, {
                    filters: this.filters,
                });
            },
        });

        this.table.onRowAction('click', 'edit', function () {
            config.events.onEdit($(this).data('tag'));
        });

        this.table.onRowAction('click', 'delete', function () {
            config.events.onDelete($(this), $(this).data('tag'));
        });
    }

    public refresh(filters: object): void {
        this.filters = filters;
        this.table.refresh();
    }

}
