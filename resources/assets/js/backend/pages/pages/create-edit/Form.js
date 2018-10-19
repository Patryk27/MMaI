import swal from 'sweetalert';
import Requester from '../../../../base/api/Requester';

export default class Form {

    /**
     * @param {Bus} bus
     * @param {jQuery} $form
     * @param {object} sections
     */
    constructor(bus, $form, sections) {
        this.$bus = bus;

        this.$dom = {
            form: $form,
            pageType: $form.find('[name="page_type"]'),
        };

        this.$state = {
            sections,

            // By the default form is clean - it means that no changes have been made to it yet.
            dirty: false,
        };
    }

    /**
     * Marks form as "dirty" (i.e. modified).
     */
    markAsDirty() {
        this.$state.dirty = true;
    }

    /**
     * @returns {boolean}
     */
    isDirty() {
        return this.$state.dirty;
    }

    /**
     * Submits the form.
     * Returns a promise which resolves to void.
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

            // After form has been saved, it's no longer "dirty"
            this.$state.dirty = false;

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
        const { attachments, notes, pageVariants } = this.$state.sections;

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

