import { GridQueryFilter } from '@/modules/grid/model/GridQueryFilter';
import { GridQueryPagination } from '@/modules/grid/model/GridQueryPagination';
import { GridQuerySorting } from '@/modules/grid/model/GridQuerySorting';

export interface GridQuery {
    filters: Array<GridQueryFilter>,
    pagination: GridQueryPagination,
    sorting: GridQuerySorting,
}
