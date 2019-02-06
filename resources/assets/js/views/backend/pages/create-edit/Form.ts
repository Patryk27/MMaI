import { PagesFacade } from '@/api/pages/PagesFacade';
import { EventBus } from '@/utils/EventBus';
import { AttachmentsSection } from './AttachmentsSection';
import { NotesSection } from './NotesSection';
import { PageSection } from './PageSection';

export class Form {

    private readonly form: JQuery;

    private readonly sections: {
        attachments: AttachmentsSection,
        notes: NotesSection,
        page: PageSection,
    };

    constructor() {
        const bus = new EventBus();

        this.form = $('#form');

        this.sections = {
            attachments: new AttachmentsSection(bus),
            notes: new NotesSection(bus),
            page: new PageSection(bus),
        };
    }

    public async submit(): Promise<void> {
        try {
            const request = Object.assign(this.sections.page.serialize(), {
                attachmentIds: this.sections.attachments.serialize(),
                notes: this.sections.notes.serialize(),
            });

            let response;

            if (this.form.data('action') === 'create') {
                response = await PagesFacade.create(request);
            } else {
                response = await PagesFacade.update(this.form.data('id'), request);
            }

            console.log(response);
        } catch (error) {
            console.log(error);
        }
    }

}

