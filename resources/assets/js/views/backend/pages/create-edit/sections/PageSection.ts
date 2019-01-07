import SimpleMDE from 'simplemde';
import { TagsFacade } from '../../../../../api/tags/TagsFacade';
import { Form, Input, Select } from '../../../../../ui/components';
import { EventBus } from '../../../../../utils/EventBus';

export class PageSection {

    private readonly form: Form;
    private readonly simpleMde: SimpleMDE;

    constructor(bus: EventBus, container: JQuery) {
        this.form = new Form({
            form: container.find('form'),

            fields: {
                id: new Input(container.find('[name="id"]')),
                type: new Input(container.find('[name="type"]')),

                url: new Input(container.find('[name="url"]')),
                title: new Input(container.find('[name="title"]')),
                lead: new Input(container.find('[name="lead"]')),
                content: new Input(container.find('[name="content"]')),

                tags: new Select(container.find('[name="tag_ids"]')),
                status: new Select(container.find('[name="status"]')),
                website: new Select(container.find('[name="website_id"]')),
            },
        });

        this.form.on('change', () => {
            bus.emit('form::invalidate');
        });

        this.form.on('keypress', (evt: JQuery.KeyboardEventBase) => {
            if (evt.originalEvent.code === 'Enter') {
                bus.emit('form::submit');
            }
        });

        this.form.onField('website', 'change', () => {
            // noinspection JSIgnoredPromiseFromCall
            this.refreshTags();
        });

        this.simpleMde = new SimpleMDE({
            autoDownloadFontAwesome: false,
            element: this.form.find('content').getHandle().get(0),
            forceSync: true,
            spellChecker: false,
        });

        bus.on('tabs::changed', ({ currentSection }) => {
            if (currentSection === 'page') {
                this.focus();
            }
        });

        this.focus();

        // noinspection JSIgnoredPromiseFromCall
        this.refreshTags();
    }

    public serialize(): object {
        return {};
        // return this.$form.serialize();
    }

    private focus(): void {
        // this.$form.title.focus();
        // this.$simpleMde.codemirror.refresh();
    }

    private async refreshTags(): Promise<void> {
        const tagsSelect: Select = this.form.find('tags');
        const websiteSelect: Select = this.form.find('website');

        tagsSelect.disable();
        websiteSelect.disable();

        try {
            const tags = await TagsFacade.search({
                filters: {
                    websiteId: websiteSelect.getValue(),
                },

                orderBy: {
                    name: 'asc',
                },
            });

            tagsSelect.setOptions(tags.map((tag) => {
                return {
                    id: tag.id,
                    label: tag.name,
                };
            }));
        } finally {
            tagsSelect.enable();
            websiteSelect.enable();
        }
    }

}
