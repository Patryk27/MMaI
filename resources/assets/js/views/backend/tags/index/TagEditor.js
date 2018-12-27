import InputComponent from '../../../../components/InputComponent';
import ButtonComponent from '../../../../components/ButtonComponent';
import TagsFacade from '../../../../api/tags/TagsFacade';
import swal from 'sweetalert';

export default class TagEditor {

    /**
     * @param {EventBus} bus
     * @param {jQuery} $modal
     */
    constructor(bus, $modal) {
        this.$bus = bus;

        this.$dom = {
            modal: $modal,
            form: $modal.find('form'),
        };

        this.$form = {
            name: new InputComponent(
                $modal.find('[name="name"]'),
            ),
        };

        // @todo rename this field + reduce DRY in these modals
        this.$formFieldsMapping = {
            name: 'name',
        };

        this.$buttons = {
            close: new ButtonComponent(
                $modal.find('.btn-close'),
            ),

            submit: new ButtonComponent(
                $modal.find('.btn-submit'),
            ),
        };

        this.$state = {
            tag: null,
        };

        // When modal is being shown, focus on the "name" field
        this.$dom.modal.on('shown.bs.modal', () => {
            this.$form.name.focus();
        });

        // Bind custom handler for the form's "submit" event
        this.$dom.form.on('submit', () => {
            // noinspection JSIgnoredPromiseFromCall
            this.$submit();

            return false;
        });
    }

    /**
     * Opens the tag editor.
     *
     * @param {object} tag
     */
    edit(tag) {
        this.$state.tag = tag;

        this.$form.name.setValue(tag.name);
        this.$dom.modal.modal();
    }

    /**
     * @private
     */
    $close() {
        this.$dom.modal.modal('hide');
    }

    /**
     * @private
     *
     * @param {string} state
     */
    $changeState(state) {
        const buttons = this.$buttons;

        switch (state) {
            case 'submitting':
                buttons.close.disable();

                buttons.submit.disable();
                buttons.submit.showSpinner();

                break;

            case 'ready':
                buttons.close.enable();

                buttons.submit.enable();
                buttons.submit.hideSpinner();

                break;
        }
    }

    /**
     * @private
     *
     * @returns {Promise<void>}
     */
    async $submit() {
        this.$changeState('submitting');

        const $form = this.$form;

        try {
            // Clear the form's errors
            for (const [, component] of Object.entries($form)) {
                component.removeFeedback();
            }

            // Execute request to the API
            await TagsFacade.update(this.$state.tag.id, {
                name: $form.name.getValue(),
            });

            // Emit "tag updated" event
            this.$bus.emit('tag::updated');

            // Show confirmation to the user
            await swal({
                title: 'Success',
                text: 'Tag has been updated.',
                icon: 'success',
            });

            // Eventually - close the modal
            this.$close();
        } catch (error) {
            if (error.getType && error.getType() === 'invalid-input') {
                for (const [fieldName, [fieldError]] of Object.entries(error.getPayload())) {
                    const formFieldName = this.$formFieldsMapping[fieldName];
                    const component = this.$form[formFieldName];

                    component.setFeedback('invalid', fieldError);
                }
            } else {
                swal({
                    title: 'Cannot create tag',
                    text: error.toString(),
                    icon: 'error',
                }).then(() => {
                    $form.name.focus();
                });
            }
        }

        this.$changeState('ready');
    }

}
