import { GridQueryPagination } from '@/modules/grid/model/GridQueryPagination';
import { GridQuerySorting } from '@/modules/grid/model/GridQuerySorting';
import { GridResponse } from '@/modules/grid/model/GridResponse';
import { GridSchema } from '@/modules/grid/model/GridSchema';
import { GridConfiguration } from '@/modules/grid/ui/GridConfiguration';

export interface GridTableConfiguration<Item> {
    gridSchema: GridSchema,
    gridConfig: GridConfiguration<Item>,

    refresh(
        sorting: GridQuerySorting,
        pagination: GridQueryPagination,
    ): Promise<GridResponse<Item>>,
}
