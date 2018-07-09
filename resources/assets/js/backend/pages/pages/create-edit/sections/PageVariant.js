import SimpleMDE from 'simplemde';

/**
 * This class defines a section which contains information about a single page
 * variant.
 */
export default class PageVariant {

    /**
     * @param {jQuery} container
     */
    constructor(container) {
        this.$dom = {
            form: {
                container: container.find('form'),

                id: container.find('[name="id"]'),
                languageId: container.find('[name="language_id"]'),
                status: container.find('[name="status"]'),
                route: container.find('[name="route"]'),
                title: container.find('[name="title"]'),
                lead: container.find('[name="lead"]'),
                content: container.find('[name="content"]'),
            },

            enabled: {
                alert: container.find('.is-enabled-alert'),
                checkbox: container.find('[name="is_enabled"]'),
            },
        };

        this.$dom.form.container.find('[data-field="lead"]').hide(); // @todo this should depend on whether page is a CMS or a blog one

        // When user clicks on the "is enabled" checkbox, refresh the section
        this.$dom.enabled.checkbox.on('change', () => {
            this.refresh();
        });

        // Initialize SimpleMDE
        this.$simpleMde = new SimpleMDE({
            element: this.$dom.form.content[0],
            forceSync: true,
        });

        this.refresh();
    }

    /**
     * Refreshes the section, brings the focus back etc.
     */
    refresh() {
        this.$toggle(
            this.$isEnabled(),
        );

        if (this.$isEnabled()) {
            setTimeout(() => {
                this.$dom.form.title.focus();
                this.$simpleMde.codemirror.refresh();
            }, 0);
        }
    }

    /**
     * Serializes the section's form.
     *
     * @returns {object}
     */
    serialize() {
        if (this.$isEnabled()) {
            return {
                id: this.$dom.form.id.val(),
                language_id: this.$dom.form.languageId.val(),
                status: this.$dom.form.status.val(),
                route: this.$dom.form.route.val(),
                title: this.$dom.form.title.val(),
                lead: this.$dom.form.lead.val(),
                content: this.$dom.form.content.val(),
            };
        } else {
            return null;
        }
    }

    /**
     * Fired before form is submitted.
     */
    handleBeforeSubmit() {
        this.$block(true);
    }

    /**
     * Fired after form has been submitted.
     */
    handleAfterSubmit() {
        this.$block(false);
    }

    /**
     * @private
     *
     * Blocks or unblocks the section.
     *
     * @param {boolean} blocked
     */
    $block(blocked) {
        // Block / unblock the "is enabled" checkbox
        this.$dom.enabled.checkbox.attr('disabled', blocked);

        // Block / unblock everything inside the form
        this.$dom.form.container.find('*').attr('disabled', blocked);

        // Block / unblock the SimpleMDE
        this.$simpleMde.codemirror.setOption('readOnly', blocked);
    }

    /**
     * @private
     *
     * Shows / hides the section.
     *
     * @param {boolean} enabled
     */
    $toggle(enabled) {
        this.$dom.enabled.alert.toggle(!enabled);
        this.$dom.form.container.toggle(enabled);
    }

    /**
     * @private
     *
     * Returns `true` if this page variant is enabled.
     *
     * @returns {boolean}
     */
    $isEnabled() {
        if (this.$dom.enabled.checkbox.length === 0) {
            return true;
        }

        return this.$dom.enabled.checkbox.is(':checked');
    }

}