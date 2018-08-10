import swal from 'sweetalert';
import Requester from '../../../../base/api/Requester';

export default class FormSubmitter {

    /**
     * @param {Bus} bus
     * @param {jQuery} $form
     * @param {object} formSections
     */
    constructor(bus, $form, formSections) {
        this.$bus = bus;

        this.$dom = {
            form: $form,
        };

        this.$state = {
            formSections,
        };
    }

    /**
     * Submits the form.
     */
    async submit() {
        this.$bus.emit('form::submitting');

        try {
            const $form = this.$dom.form;

            const response = await Requester.execute(
                $form.data('method'),
                $form.data('url'),
                this.$serialize()
            );

            this.$bus.emit('form::submitted', {
                response,
            });
        } catch (error) {
            if (error.type === 'invalid-input') {
                this.$bus.emit('form::invalid-input', error.payload); // @todo
            } else {
                // noinspection JSIgnoredPromiseFromCall
                swal({
                    title: 'Cannot save page',
                    text: error.message,
                    icon: 'error',
                });
            }

            this.$bus.emit('form::submitted', {
                response: null,
            });
        }
    }

    /**
     * @returns {object}
     */
    $serialize() {
        const pageVariants = this.$state.formSections.pageVariants
            .filter((component) => component.isEnabled())
            .map((component) => component.serialize());

        const mediaLibrary = this.$state.formSections.mediaLibrary.serialize();

        return {
            page: {
                type: 'cms', // @todo shouldn't be hard-coded; maybe we can fetch it from this.$dom.form?
            },

            pageVariants,
            mediaLibrary,
        };
    }

}

