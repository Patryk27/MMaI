import swal from 'sweetalert';

import ButtonComponent from '../../../../base/components/ButtonComponent.js';
import InputComponent from '../../../../base/components/InputComponent';
import TagsFacade from '../../../../base/api/TagsFacade';

/**
 * This component models the "create tag" modal.
 */
export default class CreateTagModalComponent {

    /**
     * @param {Bus} bus
     * @param {string} selector
     */
    constructor(bus, selector) {
        this.$bus = bus;

        const $modal = $(selector);

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

        this.$formFieldsMapping = {
            name: 'name',
            language_id: 'languageId',
        };

        this.$buttons = {
            submit: new ButtonComponent(
                $modal.find('.btn-submit'),
            ),

            close: new ButtonComponent(
                $modal.find('.btn-close'),
            ),
        };

        // When modal is being shown, clear the "name" field and focus on it
        this.$dom.modal.on('shown.bs.modal', () => {
            this.$form.name.setValue('');
            this.$form.name.focus();
        });

        // Bind custom handler for the "submit" action
        this.$dom.form.on('submit', () => {
            // noinspection JSIgnoredPromiseFromCall
            this.$submit();

            return false;
        });
    }

    /**
     * Changes the modal's active language id.
     *
     * @param {number} languageId
     */
    setLanguageId(languageId) {
        this.$form.languageId.setValue(languageId);
    }

    /**
     * Opens the modal.
     */
    open() {
        this.$dom.modal.modal();
    }

    /**
     * Closes the modal.
     */
    close() {
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
                buttons.submit.block();
                buttons.submit.showSpinner();

                buttons.close.block();

                break;

            case 'ready':
                buttons.submit.unblock();
                buttons.submit.hideSpinner();

                buttons.close.unblock();

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
            for (const [, component] of Object.entries($form)) {
                component.removeFeedback();
            }

            await TagsFacade.create({
                name: $form.name.getValue(),
                language_id: $form.languageId.getValue(),
            });

            this.$bus.emit('tag::created');

            swal({
                title: 'Success',
                text: 'Tag has been created.',
                icon: 'success',
            }).then(() => {
                this.close();
            });
        } catch (error) {
            if (error.type === 'invalid-input') {
                for (const [fieldName, [fieldError]] of Object.entries(error.payload)) {
                    const formFieldName = this.$formFieldsMapping[fieldName];
                    const component = this.$form[formFieldName];

                    component.setFeedback('invalid', fieldError);
                }
            } else {
                swal({
                    title: 'Cannot create tag',
                    text: error.message,
                    icon: 'error',
                }).then(() => {
                    $form.name.focus();
                });
            }
        }

        this.$changeState('ready');
    }

}