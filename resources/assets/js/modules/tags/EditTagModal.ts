import { FormModal } from '@/modules/core/FormModal';
import { EditTagForm } from '@/modules/tags/EditTagForm';
import { Tag } from '@/modules/tags/Tag';

export class EditTagModal {

    private modal: FormModal<Tag, EditTagForm>;

    public constructor(modal: JQuery) {
        this.modal = new FormModal(modal, new EditTagForm(modal));
    }

    public show(tag: Tag): Promise<Tag | null> {
        return this.modal.show(tag);
    }

}
