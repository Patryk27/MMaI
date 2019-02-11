import { GenericForm } from '@/modules/core/GenericForm';
import { Tag } from '@/modules/tags/Tag';
import { TagsFacade } from '@/modules/tags/TagsFacade';
import { Input } from '@/ui/components/Input';
import { Form } from '@/ui/form/Form';
import { FormInputControl } from '@/ui/form/FormInputControl';

export class EditTagForm implements GenericForm<Tag> {

    private form: Form;
    private tag?: Tag;

    public constructor(container: JQuery) {
        this.form = new Form({
            controls: [
                new FormInputControl('name', Input.fromContainer(container, 'name')),
            ],
        });
    }

    public reset(tag: Tag): void {
        this.form.find<FormInputControl>('name').input.value = tag.name;
        this.tag = tag;
    }

    public async submit(): Promise<Tag | null> {
        this.form.clearErrors();

        try {
            Object.assign(this.tag, this.form.serialize());

            return await TagsFacade.update(this.tag);
        } catch (error) {
            if (error.formErrors) {
                this.form.addErrors(error.formErrors);
            } else {
                throw error;
            }
        }

        return null;
    }

}
