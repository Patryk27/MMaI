import swal from 'sweetalert';
import { Tag } from '../../../../api/tags/Tag';
import { TagsFacade } from '../../../../api/tags/TagsFacade';
import { EventBus } from '../../../../utils/EventBus';

export class TagsDeleter {

    private readonly bus: EventBus;

    constructor(bus: EventBus) {
        this.bus = bus;
    }

    public async run(tag: Tag): Promise<void> {
        const result = await swal({
            title: 'Are you sure?',
            text: `Do you want to delete tag [${tag.name}]?`,
            icon: 'warning',
            dangerMode: true,

            buttons: {
                cancel: {
                    text: 'Cancel',
                },

                confirm: {
                    text: 'Delete',
                    closeModal: false,
                },
            },
        });

        if (!result) {
            return;
        }

        swal.close();

        try {
            await TagsFacade.delete(tag.id);

            // noinspection JSIgnoredPromiseFromCall
            swal({
                title: 'Success',
                text: 'Tag has been deleted.',
                icon: 'success',
            });

            this.bus.emit('tag::deleted');
        } catch (error) {
            // noinspection JSIgnoredPromiseFromCall
            swal({
                title: 'Cannot delete tag',
                text: error.toString(),
                icon: 'error',
            });
        }
    }

}
