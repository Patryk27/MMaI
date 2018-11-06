import swal from 'sweetalert';

import ButtonComponent from '../../../../base/components/ButtonComponent.js';
import InputComponent from '../../../../base/components/InputComponent';
import TagsFacade from '../../../../base/api/TagsFacade';

/**
 * This component models the "create tag" modal.
 */
export default class TagCreator {

    /**
     * @param {Bus} bus
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

            languageId: new InputComponent(
                $modal.find('[name="language_id"]'),
            ),
        };

        // @todo rename this field + reduce DRY in these modals
        this.$formFieldsMapping = {
            name: 'name',
            language_id: 'languageId',
        };

        this.$buttons = {
            close: new ButtonComponent(
                $modal.find('.btn-close'),
            ),

            submit: new ButtonComponent(
                $modal.find('.btn-submit'),
            ),
        };

        // When modal is being shown, clear the "name" field and focus on it
        this.$dom.modal.on('shown.bs.modal', () => {
            this.$form.name.setValue('');
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
     * Changes the active language id.
     *
     * @param {number} languageId
     */
    setLanguageId(languageId) {
        this.$form.languageId.setValue(languageId);
    }

    /**
     * Opens the tag creator.
     */
    create() {
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
            await TagsFacade.create({
                name: $form.name.getValue(),
                language_id: $form.languageId.getValue(),
            });

            // Emit "tag created" event
            this.$bus.emit('tag::created');

            // Show confirmation to the user
            await swal({
                title: 'Success',
                text: 'Tag has been created.',
                icon: 'success',
            });

            // Eventually - close the modal
            this.$close();
        } catch (error) {
            if (error.getType && error.getType() === 'invalid-input') {
                for (const [fieldName, [fieldError]] of Object.entries(error.payload)) {
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
