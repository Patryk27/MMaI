import { Form } from '@/modules/core/Form';
import { Tag } from '@/modules/tags/Tag';
import { TagsFacade } from '@/modules/tags/TagsFacade';
import { Input } from '@/ui/components/Input';
import { Form as FormComponent, FormInputControl } from '@/ui/form';

export class EditTagForm implements Form<Tag> {

    private readonly form: FormComponent;
    private tag?: Tag;

    public constructor(container: JQuery) {
        this.form = new FormComponent({
            controls: [
                new FormInputControl('name', Input.fromContainer(container, 'name')),
            ],
        });
    }

    public reset(tag: Tag | null): void {
        if (!tag) {
            throw 'No tag has been specified.';
        }

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
