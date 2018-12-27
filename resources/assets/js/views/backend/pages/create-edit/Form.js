import swal from 'sweetalert';
import PagesFacade from '../../../../api/pages/PagesFacade';

export default class Form {

    /**
     * @param {EventBus} bus
     * @param {jQuery} $form
     * @param {object} sections
     */
    constructor(bus, $form, sections) {
        this.$bus = bus;

        this.$dom = {
            form: $form,
        };

        this.$state = {
            sections,
            dirty: false,
        };
    }

    /**
     * Marks form as "modified".
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
     * @returns {Promise<void>}
     */
    async submit() {
        this.$bus.emit('form::submitting');
        let response;

        try {
            const $form = this.$dom.form;

            if ($form.data('action') === 'create') {
                response = await PagesFacade.create(this.$serialize());
            } else {
                response = await PagesFacade.update($form.data('id'), this.$serialize());
            }

            this.$state.dirty = false;
        } catch (error) {
            if (error.getType && error.getType() === 'invalid-input') {
                this.$bus.emit('form::invalid-input', error.getPayload()); // @todo implement handler for this event
            } else {
                // noinspection JSIgnoredPromiseFromCall
                swal({
                    title: 'Cannot save page',
                    text: error.toString(),
                    icon: 'error',
                });
            }
        } finally {
            this.$bus.emit('form::submitted', { response });
        }
    }

    /**
     * @returns {object}
     */
    $serialize() {
        const { attachments, notes, page } = this.$state.sections;

        return Object.assign(page.serialize(), {
            attachment_ids: attachments.serialize(),
            notes: notes.serialize(),
        });
    }

}

