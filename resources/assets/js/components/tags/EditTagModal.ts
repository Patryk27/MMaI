import { Tag } from '@/api/tags/Tag';
import { GenericModalForm } from '@/components/core/GenericModalForm';
import { EditTagForm } from '@/components/tags/EditTagForm';

export class EditTagModal {

    private modal: GenericModalForm<Tag, EditTagForm>;

    public constructor(modal: JQuery) {
        this.modal = new GenericModalForm(modal, new EditTagForm(modal));
    }

    public show(tag: Tag): Promise<Tag | null> {
        return this.modal.show(tag);
    }

}
