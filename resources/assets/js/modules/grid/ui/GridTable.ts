import { GridQueryPagination } from '@/modules/grid/model/GridQueryPagination';
import { GridQuerySorting } from '@/modules/grid/model/GridQuerySorting';
import { GridTableConfiguration } from '@/modules/grid/ui/GridTableConfiguration';
import $ from 'jquery';

export class GridTable<Item> {
    private readonly table: JQuery;
    private readonly dataTable: any;

    public constructor(private readonly config: GridTableConfiguration<Item>) {
        // Build table
        this.table =
            $('<table>')
                .addClass('table table-dark table-striped table-hover')
                .appendTo(config.gridConfig.dom.grid);

        // Build table's head
        {
            const thead = $('<thead>').appendTo(this.table);
            const tr = $('<tr>').appendTo(thead);

            config.gridSchema.fields.forEach((field) => {
                $('<th>')
                    .text(field.name)
                    .appendTo(tr);
            });
        }

        // Build table's body
        {
            $('<tbody>').appendTo(this.table);
        }

        // Initialize DataTable
        // @ts-ignore
        this.dataTable = this.table.DataTable({
            autoWidth: false,
            deferLoading: true,
            orderMulti: false,
            processing: true,
            searchDelay: 500,
            serverSide: true,

            columns: config.gridSchema.fields.map((field) => {
                return {
                    name: field.id,
                    orderable: field.sortable,
                };
            }),

            ajax: (originalData: any, callback: any) => {
                const { sorting, pagination } = this.buildGridQuery(originalData);

                config
                    .refresh(sorting, pagination)
                    .then((response) => {
                        callback({
                            recordsTotal: response.totalCount,
                            recordsFiltered: response.matchingCount,
                            data: this.sanitizeItemsForDatatable(response.items),
                        });
                    })
                    .catch(window.onerror);
            },

            initComplete: () => {
                setTimeout(() => {
                    this.refresh();
                });
            },
        });
    }

    public refresh() {
        this.dataTable.ajax.reload();
    }

    private buildGridQuery(originalData: any): { sorting: GridQuerySorting, pagination: GridQueryPagination } {
        const columnNames = originalData.columns.map((column: any): string => column.name);

        return {
            sorting: {
                field: columnNames[originalData.order[0].column],
                direction: originalData.order[0].dir,
            },

            pagination: {
                page: 1 + originalData.start / originalData.length,
                perPage: originalData.length,
            },
        };
    }

    private sanitizeItemsForDatatable(items: Array<Item>): Array<Array<string>> {
        const gridSchema = this.config.gridSchema;
        const gridConfig = this.config.gridConfig;

        return items.map((item) => {
            const columns: Array<string> = [];

            gridSchema.fields.forEach((field) => {
                if (gridConfig.fields.hasOwnProperty(field.id)) {
                    columns.push(
                        gridConfig.fields[field.id].render(item)[0].outerHTML,
                    );
                } else {
                    columns.push(
                        // @ts-ignore
                        item.hasOwnProperty(field.id) ? item[field.id] : '',
                    );
                }
            });

            return columns;
        });
    }
}
