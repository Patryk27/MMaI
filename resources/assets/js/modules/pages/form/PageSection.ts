import { TagsFacade } from '@/modules/tags/TagsFacade';
import { Input, Select } from '@/ui/components';
import { Form, FormControl, FormError, FormInputControl, FormSelectControl } from '@/ui/form';
import SimpleMDE from 'simplemde';

export class PageSection implements FormControl {

    public readonly id: string = 'page';

    private readonly form: Form;
    private readonly simpleMde: SimpleMDE;

    constructor() {
        const container = $('#page-form');

        this.form = new Form({
            controls: [
                new FormInputControl('id', Input.fromContainer(container, 'id')),
                new FormInputControl('type', Input.fromContainer(container, 'type')),

                new FormInputControl('url', Input.fromContainer(container, 'url')),
                new FormInputControl('title', Input.fromContainer(container, 'title')),
                new FormInputControl('lead', Input.fromContainer(container, 'lead')),
                new FormInputControl('content', Input.fromContainer(container, 'content')),

                new FormSelectControl('status', Select.fromContainer(container, 'status')),
                new FormSelectControl('tag_ids', Select.fromContainer(container, 'tag_ids[]')),
                new FormSelectControl('website_id', Select.fromContainer(container, 'website_id')),
            ],
        });

        this.form.find<FormSelectControl>('website_id').select.on('change', () => {
            this.refreshTags().catch(window.onerror);
        });

        this.simpleMde = new SimpleMDE({
            autoDownloadFontAwesome: false,
            element: container.find('[name=content]')[0],
            forceSync: true,
            spellChecker: false,
        });

        this.focus();
        this.refreshTags().catch(window.onerror);
    }

    public addError(error: FormError): void {
        this.form.addError(error);
    }

    public clearErrors(): void {
        this.form.clearErrors();
    }

    public focus(): void {
        this.form.find('title').focus();
    }

    public serialize(): any {
        return this.form.serialize();
    }

    private async refreshTags(): Promise<void> {
        const tagsSelect = this.form.find<FormSelectControl>('tag_ids');
        const websiteSelect = this.form.find<FormSelectControl>('website_id');

        tagsSelect.select.disable();
        websiteSelect.select.disable();

        try {
            const tags = await TagsFacade.search({
                filters: {
                    websiteId: websiteSelect.select.value,
                },

                orderBy: {
                    name: 'asc',
                },
            });

            tagsSelect.select.options = tags.map((tag) => {
                return { id: tag.id, label: tag.name };
            });
        } finally {
            tagsSelect.select.enable();
            websiteSelect.select.enable();
        }
    }

}
