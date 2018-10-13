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
            pageType: $form.find('[name="page_type"]'),
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

            const response = await Requester.execute({
                method: $form.data('method'),
                url: $form.data('url'),
                data: this.$serialize(),
            });

            this.$bus.emit('form::submitted', { response });
        } catch (error) {
            if (error.type === 'invalid-input') {
                this.$bus.emit('form::invalid-input', error.payload); // @todo highlight the invalid input
            } else {
                // noinspection JSIgnoredPromiseFromCall
                swal({
                    title: 'Cannot save page',
                    text: error.message,
                    icon: 'error',
                });
            }

            this.$bus.emit('form::submitted', { response: null });
        }
    }

    /**
     * @returns {object}
     */
    $serialize() {
        const { attachments, notes, pageVariants } = this.$state.formSections;

        return {
            page: {
                type: this.$dom.pageType.val(),
                notes: notes.serialize(),
            },

            pageVariants: pageVariants
                .filter((pageVariant) => pageVariant.isEnabled())
                .map((pageVariant) => pageVariant.serialize()),

            attachment_ids: attachments.serialize(),
        };
    }

}

