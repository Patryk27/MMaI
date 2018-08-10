import swal from 'sweetalert';
import ButtonComponent from '../../../../base/components/ButtonComponent.js';
import TagsFacade from '../../../../base/api/TagsFacade';

/**
 * This component models the "create tag" modal.
 */
export default class CreateTagModalComponent {

    /**
     * @param {string} selector
     */
    constructor(selector) {
        const $modal = $(selector);

        this.$dom = {
            modal: $modal,

            form: {
                container: $modal.find('form'),

                name: $modal.find('[name="name"]'),
                languageId: $modal.find('[name="language_id"]'),
            },
        };

        this.$buttons = {
            submit: new ButtonComponent(
                $modal.find('.btn-submit'),
            ),

            close: new ButtonComponent(
                $modal.find('.btn-close'),
            ),
        };

        // @todo
        // this.$inputs = {
        //     name: new Input(
        //         $modal.find('[name="name"]'),
        //     ),
        //
        //     languageId: new Input(
        //         $modal.find('[name="language_id"]'),
        //     ),
        // };

        // When modal is being shown, clear the "name" field and focus on it
        this.$dom.modal.on('shown.bs.modal', () => {
            this.$dom.form.name
                .val('')
                .focus();
        });

        // Bind custom handler for the "submit" action
        this.$dom.form.container.on('submit', () => {
            // noinspection JSIgnoredPromiseFromCall
            this.$submit();

            return false;
        });
    }

    /**
     * @param {number} selectedLanguageId
     */
    show(selectedLanguageId) {
        this.$dom.form.languageId.val(selectedLanguageId);
        this.$dom.modal.modal();
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

        try {
            const $form = this.$dom.form;

            await TagsFacade.create({
                name: $form.name.val(),
                language_id: $form.languageId.val(),
            });
        } catch (error) {
            if (error.type === 'invalid-input') {
                console.log(error.payload);
            } else {
                swal({
                    title: 'Cannot create tag',
                    text: error.message,
                    icon: 'error',
                }).then(() => {
                    this.$dom.form.name.focus();
                });
            }
        }

        this.$changeState('ready');
    }

}