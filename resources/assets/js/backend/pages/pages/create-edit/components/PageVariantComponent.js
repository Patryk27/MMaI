import SimpleMDE from 'simplemde';

import CheckboxComponent from '../../../../../base/components/CheckboxComponent';
import InputComponent from '../../../../../base/components/InputComponent';

export default class PageVariantComponent {

    /**
     * @param {Bus} bus
     * @param {jQuery} container
     */
    constructor(bus, container) {
        this.$dom = {
            form: container.find('form'),
            isDisabledAlert: container.find('.is-disabled-alert'),
        };

        this.$form = {
            isEnabled: new CheckboxComponent(
                container.find('[name="is_enabled"]')
            ),

            id: new InputComponent(
                container.find('[name="id"]'),
            ),

            languageId: new InputComponent(
                container.find('[name="language_id"]'),
            ),

            route: new InputComponent(
                container.find('[name="route"]'),
            ),

            title: new InputComponent(
                container.find('[name="title"]'),
            ),

            tagIds: new InputComponent(
                container.find('[name="tag_ids"]'),
            ),

            status: new InputComponent(
                container.find('[name="status"]'),
            ),

            lead: new InputComponent(
                container.find('[name="lead"]'),
            ),

            content: new InputComponent(
                container.find('[name="content"]'),
            ),
        };

        // Initialize SimpleMDE
        this.$simpleMde = new SimpleMDE({
            element: this.$dom.form.find('[name="content"]')[0],
            forceSync: true,
        });

        // When user clicks on the "is enabled" checkbox, refresh the section
        this.$form.isEnabled.on('change', () => {
            this.$refresh();
        });

        // When user changes a tab, we may need to refresh ourselves, since SimpleMDE after going from "hidden" into
        // "visible" tends to forget that it should re-render
        bus.on('tabs::changed', () => {
            this.$refresh();
        });

        // When form is being submitted, block the section
        bus.on('form::submitting', () => {
            this.$block(true);
        });

        // When form has been submitted, unblock the section
        bus.on('form::submitted', () => {
            this.$block(false);
        });

        this.$refresh();
    }

    /**
     * @returns {boolean}
     */
    isEnabled() {
        const isEnabled = this.$form.isEnabled;

        // The "is enabled" checkbox may not be present when we are updating the page variant
        if (!isEnabled.exists()) {
            return true;
        }

        return isEnabled.isChecked();
    }

    /**
     * @returns {object}
     */
    serialize() {
        const $form = this.$form;

        return {
            id: $form.id.getValue(),
            language_id: $form.languageId.getValue(),
            route: $form.route.getValue(),
            title: $form.title.getValue(),
            tag_ids: $form.tagIds.getValue(),
            status: $form.status.getValue(),
            lead: $form.lead.getValue(),
            content: $form.content.getValue(),
        };
    }

    /**
     * @private
     *
     * Refreshes the section, brings the focus back etc.
     */
    $refresh() {
        this.$toggle(
            this.isEnabled(),
        );

        if (this.isEnabled()) {
            this.$form.title.focus();
            this.$simpleMde.codemirror.refresh();
        }
    }

    /**
     * @private
     *
     * Shows / hides the component.
     *
     * @param {boolean} enabled
     */
    $toggle(enabled) {
        this.$dom.form.toggle(enabled);
        this.$dom.isDisabledAlert.toggle(!enabled);
    }

    /**
     * @private
     *
     * Blocks / unblocks the component.
     *
     * @param {boolean} blocked
     */
    $block(blocked) {
        for (const [, component] of Object.entries(this.$form)) {
            if (blocked) {
                component.block();
            } else {
                component.unblock();
            }
        }

        // Block / unblock the SimpleMDE
        this.$simpleMde.codemirror.setOption('readOnly', blocked);
    }

}