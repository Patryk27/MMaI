import { GridConfiguration } from '@/modules/grid/Grid.configuration';
import { GridQueryPagination } from '@/modules/grid/query/GridQueryPagination';
import { GridQuerySorting } from '@/modules/grid/query/GridQuerySorting';
import { GridResponse } from '@/modules/grid/response/GridResponse';
import { GridSchema } from '@/modules/grid/schema/GridSchema';

export interface GridTableConfiguration<Item> {
    gridSchema: GridSchema,
    gridConfig: GridConfiguration<Item>,

    refresh(
        sorting: GridQuerySorting,
        pagination: GridQueryPagination,
    ): Promise<GridResponse<Item>>,
}
