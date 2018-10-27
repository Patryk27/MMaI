import swal from 'sweetalert';
import TagsFacade from '../../../../base/api/TagsFacade';

export default class TagDeleter {

    /**
     * @param {Bus} bus
     */
    constructor(bus) {
        this.$bus = bus;
    }

    /**
     * Confirms if user wants to delete given tag and if so, deletes it.
     *
     * @param {object} tag
     * @returns {Promise<void>}
     */
    async delete(tag) {
        const result = await swal({
            title: 'Are you sure?',
            text: `Do you want to delete tag [${tag.name}]?`,
            icon: 'warning',
            dangerMode: true,

            buttons: {
                cancel: 'Cancel',

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

            this.$bus.emit('tag::deleted');
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
