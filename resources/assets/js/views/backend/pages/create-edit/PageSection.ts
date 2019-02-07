import { TagsFacade } from '@/api/tags/TagsFacade';
import { Field, Form, Input, Select } from '@/ui/components';
import { EventBus } from '@/utils/EventBus';
import SimpleMDE from 'simplemde';

export class PageSection {

    private readonly form: Form;
    private readonly simpleMde: SimpleMDE;

    constructor(bus: EventBus) {
        const container = $('#page');

        this.form = new Form({
            form: container.find('form'),

            fields: [
                Field.input('id', container),
                Field.input('type', container),

                Field.input('url', container),
                Field.input('title', container),
                Field.input('lead', container),
                Field.input('content', container),

                Field.select('status', container),
                Field.select('tagIds', container),
                Field.select('websiteId', container),
            ],
        });

        this.form.on('change', () => {
            bus.emit('invalidated');
        });

        this.form.on('keypress', (evt: JQuery.KeyboardEventBase) => {
            if (evt.originalEvent.code === 'Enter') {
                bus.emit('submit');
            }
        });

        this.form.onField('websiteId', 'change', () => {
            this.refreshTags().catch(window.onerror);
        });

        this.simpleMde = new SimpleMDE({
            autoDownloadFontAwesome: false,
            element: this.form.find('content').as<Input>().getHandle().get(0),
            forceSync: true,
            spellChecker: false,
        });

        this.focus();
        this.refreshTags().catch(window.onerror);
    }

    public serialize(): object {
        return this.form.serialize();
    }

    public focus(): void {
        // this.$form.title.focus();
        // this.$simpleMde.codemirror.refresh();
    }

    private async refreshTags(): Promise<void> {
        const tagsSelect: Select = this.form.find('tagIds').as();
        const websiteSelect: Select = this.form.find('websiteId').as();

        tagsSelect.disable();
        websiteSelect.disable();

        try {
            const tags = await TagsFacade.search({
                filters: {
                    websiteId: websiteSelect.serialize(),
                },

                orderBy: {
                    name: 'asc',
                },
            });

            tagsSelect.setOptions(tags.map((tag) => {
                return { id: tag.id, label: tag.name };
            }));
        } finally {
            tagsSelect.enable();
            websiteSelect.enable();
        }
    }

}
