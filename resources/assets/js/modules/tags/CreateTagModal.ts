import { GenericModalForm } from '@/modules/core/GenericModalForm';
import { CreateTagForm } from '@/modules/tags/CreateTagForm';
import { Tag } from '@/modules/tags/Tag';

export class CreateTagModal {

    private modal: GenericModalForm<Tag, CreateTagForm>;

    public constructor(modal: JQuery) {
        this.modal = new GenericModalForm(modal, new CreateTagForm(modal));
    }

    public show(): Promise<Tag | null> {
        return this.modal.show();
    }

}
