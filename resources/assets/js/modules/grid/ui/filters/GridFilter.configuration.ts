import { GridQueryFilter } from '@/modules/grid/query/GridQueryFilter';

export interface GridFilterConfiguration {
    filter: GridQueryFilter,

    /**
     * Fired when user wants to edit this filter.
     */
    onEdit(filter: GridQueryFilter): void,

    /**
     * Fired when user wants to delete this filter.
     */
    onDelete(filter: GridQueryFilter): void,
}
