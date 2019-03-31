import { FormModal } from '@/modules/core/FormModal';
import { GridQueryFilter } from '@/modules/grid/query/GridQueryFilter';
import { GridQueryFilterForm } from '@/modules/grid/query/GridQueryFilter.form';
import { GridSchema } from '@/modules/grid/schema/GridSchema';

export class GridQueryFilterModal {
    private modal: FormModal<GridQueryFilter, GridQueryFilterForm>;

    public constructor(container: JQuery, gridSchema: GridSchema) {
        this.modal = new FormModal(container, new GridQueryFilterForm(container, gridSchema));
    }

    public show(filter: GridQueryFilter | null): Promise<GridQueryFilter | null> {
        return this.modal.show(filter);
    }
}
