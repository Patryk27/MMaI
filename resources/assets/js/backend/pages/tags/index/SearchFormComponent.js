export default class SearchFormComponent {

    /**
     * @param {string} formSelector
     */
    constructor(formSelector) {
        const $form = $(formSelector);

        this.$dom = {
            form: $form,
            languageId: $form.find('[name="language_id"]'),
        };

        this.$eventHandlers = {
            languageChanged: null,
            submit: null,
        };

        this.$dom.form.on('change', 'select', () => {
            this.$handleLanguageChanged();
            this.$handleSubmit();
        });

        // @todo explain why we're doing this setTimeout() thing
        setTimeout(() => {
            this.$handleLanguageChanged();
            this.$handleSubmit();
        }, 0);
    }

    /**
     * Binds handler for the "language changed" event.
     *
     * Handler's signature:
     *   (languageId: number) -> void
     *
     * @param {function} handler
     */
    onLanguageChanged(handler) {
        this.$eventHandlers.languageChanged = handler;
    }

    /**
     * Binds handler for the "submit" event.
     *
     * Handler's signature:
     *   (form: object) -> void
     *
     * @param {function} handler
     */
    onSubmit(handler) {
        this.$eventHandlers.submit = handler;
    }

    /**
     * @private
     */
    $handleLanguageChanged() {
        this.$eventHandlers.languageChanged(
            this.$dom.languageId.val()
        );
    }

    /**
     * @private
     */
    $handleSubmit() {
        this.$eventHandlers.submit({
            language_id: this.$dom.languageId.val(),
        });
    }

}