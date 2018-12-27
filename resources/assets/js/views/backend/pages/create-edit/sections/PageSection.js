import SimpleMDE from 'simplemde';
import InputComponent from '../../../../../components/InputComponent';
import TagsFacade from '../../../../../api/tags/TagsFacade';
import SelectComponent from '../../../../../components/SelectComponent';

export default class PageSection {

    /**
     * @param {EventBus} bus
     * @param {jQuery} $container
     */
    constructor(bus, $container) {
        this.$dom = {
            form: $container.find('form'),
        };

        this.$form = {
            id: new InputComponent($container.find('[name="id"]')),
            type: new InputComponent($container.find('[name="type"]')),

            url: new InputComponent($container.find('[name="url"]')),
            title: new InputComponent($container.find('[name="title"]')),
            lead: new InputComponent($container.find('[name="lead"]')),
            content: new InputComponent($container.find('[name="content"]')),

            tags: new SelectComponent($container.find('[name="tag_ids"]')),
            status: new SelectComponent($container.find('[name="status"]')),
            website: new SelectComponent($container.find('[name="website_id"]')),
        };

        this.$dom.form.on('change', () => {
            bus.emit('form::changed');
        });

        this.$form.website.on('change', () => {
            // noinspection JSIgnoredPromiseFromCall
            this.$refreshTags();
        });

        // When user presses enter when inside the URL or title inputs, consider it equal to clicking the "submit"
        // button
        this.$dom.form.on('keypress', 'input', (evt) => {
            if (evt.originalEvent.code === 'Enter') {
                bus.emit('form::submit');
            }
        });

        // Initialize SimpleMDE
        this.$simpleMde = new SimpleMDE({
            autoDownloadFontAwesome: false,
            element: this.$dom.form.find('[name="content"]')[0],
            forceSync: true,
            spellChecker: false,
        });

        // When user changes a tab, we may have to refresh ourselves, since SimpleMDE - after becoming visible - tends
        // to forget that it should repaint
        bus.on('tabs::changed', ({ activatedTabName }) => {
            if (activatedTabName.includes('page')) {
                this.$focus();
            }
        });

        this.$focus();

        // noinspection JSIgnoredPromiseFromCall
        this.$refreshTags();
    }

    /**
     * @returns {object}
     */
    serialize() {
        const form = this.$form;

        return {
            id: form.id.getValue(),
            type: form.type.getValue(),

            url: form.url.getValue(),
            title: form.title.getValue(),
            lead: form.lead.getValue(),
            content: form.content.getValue(),

            tag_ids: form.tags.getValue(),
            status: form.status.getValue(),
            website_id: form.website.getValue(),
        };
    }

    /**
     * @private
     */
    $focus() {
        this.$form.title.focus();
        this.$simpleMde.codemirror.refresh();
    }

    /**
     * @private
     */
    async $refreshTags() {
        this.$form.tags.disable();

        let tags = await TagsFacade.search({
            filters: {
                websiteId: this.$form.website.getValue(),
            },

            orderBy: {
                name: 'asc',
            },
        });

        tags = tags.map((tag) => {
            return {
                id: tag.id,
                label: tag.name,
            };
        });

        this.$form.tags.setOptions(tags);
        this.$form.tags.enable();
    }

}
