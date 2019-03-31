import { Form } from '@/modules/core/Form';
import { GridQueryFilter } from '@/modules/grid/query/GridQueryFilter';
import { GridSchema } from '@/modules/grid/schema/GridSchema';
import { Input, Select } from '@/ui/components';
import { Form as FormComponent, FormInputControl, FormSelectControl } from '@/ui/form';

export class GridQueryFilterForm implements Form<GridQueryFilter> {
    private readonly form: FormComponent;

    public constructor(container: JQuery, gridSchema: GridSchema) {
        this.form = new FormComponent({
            controls: [
                new FormSelectControl('field', Select.fromContainer(container, 'field')),
                new FormSelectControl('operator', Select.fromContainer(container, 'operator')),
                new FormInputControl('value', Input.fromContainer(container, 'value')),
            ],
        });

        // Add all the available fields to the "field" select
        this.form.find<FormSelectControl>('field').select.options = gridSchema.fields.map((field) => {
            return {
                id: field.id,
                label: field.name,
            };
        });
    }

    public reset(filter: GridQueryFilter | null): void {
        // @todo
    }

    public submit(): Promise<GridQueryFilter | null> {
        return new Promise((resolve) => {
            resolve(
                this.form.serialize(),
            );
        });
    }
}
