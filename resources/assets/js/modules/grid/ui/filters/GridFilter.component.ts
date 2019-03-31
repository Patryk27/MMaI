import { GridQueryFilter } from '@/modules/grid/query/GridQueryFilter';
import { GridFilterConfiguration } from '@/modules/grid/ui/filters/GridFilter.configuration';

export class GridFilterComponent {
    public constructor(private readonly config: GridFilterConfiguration) {

    }

    set filter(filter: GridQueryFilter) {
        this.config.filter = filter;
    }

    public render(): JQuery {
        const field =
            $('<kbd>')
                .text(this.config.filter.field);

        const operator =
            $('<span>')
                .text(this.config.filter.operator);

        const value =
            $('<kbd>')
                .text(this.config.filter.value); // @todo truncate if too long

        const deleteButton =
            $('<a>')
                .addClass('btn btn-icon-only grid-filter-delete')
                .attr('href', '#')
                .on('click', () => {
                    this.config.onDelete(this.config.filter);
                    return false;
                })
                .html('<i class="fa fa-times"></i>');

        return $('<div>')
            .addClass('grid-filter')
            .append(field)
            .append(operator)
            .append(value)
            .append(deleteButton)
            .on('click', () => {
                this.config.onEdit(this.config.filter);
                return false;
            });
    }
}
