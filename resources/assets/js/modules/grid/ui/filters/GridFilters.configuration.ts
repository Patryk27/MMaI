import { GridConfiguration } from '@/modules/grid/Grid.configuration';
import { GridSchema } from '@/modules/grid/schema/GridSchema';

export interface GridFiltersConfiguration<Item> {
    gridSchema: GridSchema,
    gridConfig: GridConfiguration<Item>,
}
