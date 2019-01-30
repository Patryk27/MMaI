import swal from 'sweetalert';
import { Tag } from '../../../../api/tags/Tag';
import { TagsFacade } from '../../../../api/tags/TagsFacade';
import { EventBus } from '../../../../utils/EventBus';

export class TagsDeleter {

    constructor(private readonly bus: EventBus) {
    }

    public async delete(tag: Tag): Promise<void> {
        try {
            await TagsFacade.delete(tag.id);

            this.bus.emit('tag::deleted');

            await swal({
                title: 'Success',
                text: 'Tag has been deleted.',
                icon: 'success',
            });
        } catch (error) {
            await swal({
                title: 'Failed to delete tag',
                text: error.toString(),
                icon: 'error',
            });
        }
    }

}
