import swal from 'sweetalert';

import ButtonComponent from '../../../../components/ButtonComponent.js';
import InputComponent from '../../../../components/InputComponent';
import TagsFacade from '../../../../api/tags/TagsFacade';

/**
 * This component models the "create tag" modal.
 */
export default class TagCreator {

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

            websiteId: new InputComponent(
                $modal.find('[name="website_id"]'),
            ),
        };

        // @todo rename this field + reduce DRY in these modals
        this.$formFieldsMapping = {
            name: 'name',
            website_id: 'websiteId',
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
     * Changes the active website.
     *
     * @param {number} websiteId
     */
    setWebsiteId(websiteId) {
        this.$form.websiteId.setValue(websiteId);
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
            for (const [, component] of Object.entries($form)) {
                component.removeFeedback();
            }

            await TagsFacade.create({
                name: $form.name.getValue(),
                website_id: $form.websiteId.getValue(),
            });

            this.$bus.emit('tag::created');

            await swal({
                title: 'Success',
                text: 'Tag has been created.',
                icon: 'success',
            });

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
