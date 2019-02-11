import { GenericForm } from '@/modules/core/GenericForm';
import { Tag } from '@/modules/tags/Tag';
import { TagsFacade } from '@/modules/tags/TagsFacade';
import { Input } from '@/ui/components/Input';
import { Select } from '@/ui/components/Select';
import { Form, FormInputControl } from '@/ui/form';

export class CreateTagForm implements GenericForm<Tag> {

    private readonly form: Form;

    constructor(container: JQuery) {
        this.form = new Form({
            controls: [
                new FormInputControl('name', Input.fromContainer(container, 'name')),
                new FormInputControl('website_id', Select.fromContainer(container, 'website_id')),
            ],
        });
    }

    public reset(): void {
        const name = this.form.find<FormInputControl>('name');

        name.input.value = '';
        name.input.focus();
    }

    public async submit(): Promise<Tag | null> {
        this.form.clearErrors();

        try {
            return await TagsFacade.create(this.form.serialize());
        } catch (error) {
            if (error.formErrors && error.formErrors.length > 0) {
                this.form.addErrors(error.formErrors);
            } else {
                throw error;
            }
        }

        return null;
    }

}
