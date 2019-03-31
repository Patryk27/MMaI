import { GridSchemaField } from '@/modules/grid/schema/GridSchemaField';

export interface GridSchema {
    readonly fields: Array<GridSchemaField>;
}
