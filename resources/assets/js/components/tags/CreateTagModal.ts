import { Tag } from '@/api/tags/Tag';
import { GenericModalForm } from '@/components/core/GenericModalForm';
import { CreateTagForm } from '@/components/tags/CreateTagForm';

export class CreateTagModal {

    private modal: GenericModalForm<Tag, CreateTagForm>;

    public constructor(modal: JQuery) {
        this.modal = new GenericModalForm(modal, new CreateTagForm(modal));
    }

    public show(): Promise<Tag | null> {
        return this.modal.show();
    }

}
