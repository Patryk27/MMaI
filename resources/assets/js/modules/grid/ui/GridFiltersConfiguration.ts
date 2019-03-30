import { GridSchema } from '@/modules/grid/model/GridSchema';
import { GridConfiguration } from '@/modules/grid/ui/GridConfiguration';

export interface GridFiltersConfiguration<Item> {
    gridSchema: GridSchema,
    gridConfig: GridConfiguration<Item>,
}
