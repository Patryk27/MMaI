import swal from 'sweetalert';
import { Tag } from '../../../../api/tags/Tag';
import { TagsFacade } from '../../../../api/tags/TagsFacade';
import { EventBus } from '../../../../utils/EventBus';

export class TagsDeleter {

    private readonly bus: EventBus;

    constructor(bus: EventBus) {
        this.bus = bus;
    }

    public async delete(tag: Tag): Promise<void> {
        // const result = await swal({
        //     title: 'Deleting tag',
        //     text: `Do you want to delete tag [${tag.name}]?`,
        //     icon: 'warning',
        //     dangerMode: true,
        //     buttons: {
        //         cancel: true,
        //         confirm: {
        //             text: 'Delete',
        //             closeModal: false,
        //         },
        //     },
        // });
        //
        // if (!result) {
        //     return;
        // }

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
                title: 'An error occurred',
                text: error.toString(),
                icon: 'error',
            });
        }
    }

}
