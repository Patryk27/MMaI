import { GridQueryFilter } from '@/modules/grid/query/GridQueryFilter';
import { GridQueryPagination } from '@/modules/grid/query/GridQueryPagination';
import { GridQuerySorting } from '@/modules/grid/query/GridQuerySorting';

export interface GridQuery {
    filters: Array<GridQueryFilter>,
    pagination: GridQueryPagination,
    sorting: GridQuerySorting,
}
