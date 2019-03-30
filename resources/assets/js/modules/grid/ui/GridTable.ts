import { GridSchemaField } from '@/modules/grid/model/GridSchemaField';
import { GridFieldsConfiguration } from '@/modules/grid/ui/Grid';

export class GridTable<Item> {

    private readonly table: JQuery;

    public constructor(
        container: JQuery,
        private readonly fields: Array<GridSchemaField>,
        private readonly fieldConfigs: GridFieldsConfiguration<Item>,
    ) {
        // Build table
        const table =
            $('<table>')
                .addClass('table table-dark table-striped table-hover')
                .appendTo(container);

        // Build table's head
        {
            const thead =
                $('<thead>')
                    .appendTo(table);

            const tr =
                $('<tr>')
                    .appendTo(thead);

            fields.forEach((field) => {
                $('<th>')
                    .text(field.name)
                    .appendTo(tr);
            });
        }

        // Build table's body
        {
            $('<tbody>')
                .appendTo(table);
        }

        this.table = table;
    }

    public refresh(items: Array<Item>) {
        const tbody = this.table.find('tbody');

        tbody.empty();

        items.forEach((item) => {
            this.buildRowForItem(item).appendTo(tbody);
        });
    }

    private buildRowForItem(item: Item): JQuery {
        const tr = $('<tr>');

        this.fields.forEach((field) => {
            if (this.fieldConfigs.hasOwnProperty(field.id)) {
                const cell = this.fieldConfigs[field.id].render(item);

                $('<td>')
                    .append(cell)
                    .appendTo(tr);
            } else {
                // @ts-ignore
                const cell = item.hasOwnProperty(field.id) ? item[field.id] : '';

                $('<td>')
                    .text(cell)
                    .appendTo(tr);
            }
        });

        return tr;
    }

}
