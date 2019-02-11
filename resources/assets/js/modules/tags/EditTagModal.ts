import { GenericModalForm } from '@/modules/core/GenericModalForm';
import { EditTagForm } from '@/modules/tags/EditTagForm';
import { Tag } from '@/modules/tags/Tag';

export class EditTagModal {

    private modal: GenericModalForm<Tag, EditTagForm>;

    public constructor(modal: JQuery) {
        this.modal = new GenericModalForm(modal, new EditTagForm(modal));
    }

    public show(tag: Tag): Promise<Tag | null> {
        return this.modal.show(tag);
    }

}
