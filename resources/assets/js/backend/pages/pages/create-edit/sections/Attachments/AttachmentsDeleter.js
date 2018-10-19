import swal from 'sweetalert';

export default class AttachmentsDeleter {

    /**
     * @param {Bus} bus
     * @param {AttachmentsTable} table
     */
    constructor(bus, table) {
        this.$bus = bus;
        this.$table = table;
    }

    /**
     * Asks user and deletes given attachment if given the opportunity.
     *
     * @param {object} attachment
     * @returns {Promise<void>}
     */
    async delete(attachment) {
        const result = await swal({
            title: 'Are you sure?',
            text: `Do you want to delete attachment [${attachment.name}]?`,
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

        swal.close();

        if (result) {
            this.$table.remove(attachment.id);

            // After attachment has been successfully deleted, mark form as "dirty"
            this.$bus.emit('form::changed');

            // noinspection JSIgnoredPromiseFromCall
            swal({
                title: 'Success',
                text: 'Attachment has been deleted.',
                icon: 'success',
            });
        }
    }

}
