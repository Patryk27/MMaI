import { GridQueryFilter } from '@/modules/grid/model/GridQueryFilter';
import { GridFiltersConfiguration } from '@/modules/grid/ui/GridFiltersConfiguration';

export class GridFilters<Item> {
    private readonly dom: {
        filters: JQuery,
        createFilter: JQuery,
    };

    private readonly filters: Array<GridQueryFilter> = [];

    public constructor(private readonly config: GridFiltersConfiguration<Item>) {
        const wrapper =
            $('<div>')
                .addClass('grid-filters')
                .appendTo(config.gridConfig.dom.grid);

        this.dom = {
            filters:
                $('<div>')
                    .appendTo(wrapper),

            createFilter:
                $('<button>')
                    .addClass('btn btn-primary btn-sm grid-add-filter')
                    .html('Create filter <i class="fa fa-plus"></i>')
                    .on('click', () => {
                        alert('yay');
                    })
                    .appendTo(wrapper),
        };
    }

    public get(): Array<GridQueryFilter> {
        return this.filters;
    }

    private refreshFilters(): void {
        this.dom.filters.empty();

        this.filters.forEach((filter) => {
            $('<div>')
                .addClass('grid-filter')
                .append(
                    $('<kbd>').text(filter.field),
                )
                .append(
                    $('<span>').text(filter.operator),
                )
                .append(
                    $('<kbd>').text(filter.value), // @todo truncate if value is too long
                )
                .append(
                    $('<a>')
                        .addClass('btn btn-icon-only grid-filter-delete')
                        .attr('href', '#')
                        .html('<i class="fa fa-times"></i>'),
                )
                .appendTo(this.dom.filters);
        });
    }
}
