import { FormModal } from '@/modules/core/FormModal';
import { CreateTagForm } from '@/modules/tags/CreateTagForm';
import { Tag } from '@/modules/tags/Tag';

export class CreateTagModal {

    private modal: FormModal<Tag, CreateTagForm>;

    public constructor(modal: JQuery) {
        this.modal = new FormModal(modal, new CreateTagForm(modal));
    }

    public show(): Promise<Tag | null> {
        return this.modal.show();
    }

}
