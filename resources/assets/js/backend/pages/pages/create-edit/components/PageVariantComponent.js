import SimpleMDE from 'simplemde';

import CheckboxComponent from '../../../../../base/components/CheckboxComponent';
import InputComponent from '../../../../../base/components/InputComponent';

export default class PageVariantComponent {

    /**
     * @param {Bus} bus
     * @param {jQuery} $container
     */
    constructor(bus, $container) {
        this.$dom = {
            form: $container.find('form'),
            isDisabledAlert: $container.find('.is-disabled-alert'),
        };

        this.$form = {
            isEnabled: new CheckboxComponent(
                $container.find('[name="is_enabled"]'),
            ),

            id: new InputComponent(
                $container.find('[name="id"]'),
            ),

            languageId: new InputComponent(
                $container.find('[name="language_id"]'),
            ),

            url: new InputComponent(
                $container.find('[name="url"]'),
            ),

            title: new InputComponent(
                $container.find('[name="title"]'),
            ),

            tagIds: new InputComponent(
                $container.find('[name="tag_ids"]'),
            ),

            status: new InputComponent(
                $container.find('[name="status"]'),
            ),

            lead: new InputComponent(
                $container.find('[name="lead"]'),
            ),

            content: new InputComponent(
                $container.find('[name="content"]'),
            ),
        };

        // When user clicks on the "is enabled" checkbox, let's re-render ourselves
        this.$form.isEnabled.on('change', () => {
            this.$refresh();
        });

        // When user presses enter when inside the URL or title inputs, consider it equal to clicking the "submit"
        // button
        this.$dom.form.on('keypress', (evt) => {
            if (evt.originalEvent.code === 'Enter') {
                bus.emit('form::submit');
            }
        });

        // Initialize SimpleMDE
        this.$simpleMde = new SimpleMDE({
            autoDownloadFontAwesome: false,
            element: this.$dom.form.find('[name="content"]')[0],
            forceSync: true,
        });

        // When user changes a tab, we may have to refresh ourselves. since SimpleMDE - after becoming visible - tends
        // to forget that it should repaint
        bus.on('tabs::changed', () => {
            this.$refresh();
        });

        bus.on('form::submitting', () => {
            this.$block(true);
        });

        bus.on('form::submitted', () => {
            this.$block(false);
        });

        this.$refresh();
    }

    /**
     * @returns {object}
     */
    serialize() {
        const form = this.$form;

        return {
            id: form.id.getValue(),
            language_id: form.languageId.getValue(),
            url: form.url.getValue(),
            title: form.title.getValue(),
            tag_ids: form.tagIds.getValue(),
            status: form.status.getValue(),
            lead: form.lead.getValue(),
            content: form.content.getValue(),
        };
    }

    /**
     * @returns {boolean}
     */
    isEnabled() {
        const isEnabled = this.$form.isEnabled;

        // The "is enabled" checkbox is not present when we are updating an already existing page variant - we need to
        // force-enable ourselves in such cases
        if (!isEnabled.exists()) {
            return true;
        }

        return isEnabled.isChecked();
    }

    /**
     * @private
     *
     * Re-renders the component.
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
            component.disable(blocked);
        }

        this.$simpleMde.codemirror.setOption('readOnly', blocked);
    }

}
