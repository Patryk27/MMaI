import swal from 'sweetalert';
import { PagesFacade } from '../../../../api/pages/PagesFacade';
import { EventBus } from '../../../../utils/EventBus';
import { AttachmentsSection } from './sections/AttachmentsSection';
import { NotesSection } from './sections/NotesSection';
import { PageSection } from './sections/PageSection';

type Sections = {
    attachments: AttachmentsSection,
    notes: NotesSection,
    page: PageSection,
};

export class Form {

    private readonly bus: EventBus;

    private readonly dom: {
        form: JQuery,
    };

    private readonly state: {
        sections: Sections,
        dirty: boolean,
    };

    constructor(bus: EventBus, form: JQuery, sections: Sections) {
        this.bus = bus;
        this.dom = { form };
        this.state = { sections, dirty: false };
    }

    public makeDirty(): void {
        this.state.dirty = true;
    }

    public isDirty(): boolean {
        return this.state.dirty;
    }

    public async submit(): Promise<void> {
        this.bus.emit('form::submitting');

        let response;

        try {
            const form = this.dom.form;

            if (form.data('action') === 'create') {
                response = await PagesFacade.create(this.serialize());
            } else {
                response = await PagesFacade.update(form.data('id'), this.serialize());
            }

            this.state.dirty = false;
        } catch (error) {
            if (error.getType && error.getType() === 'invalid-input') {
                this.bus.emit('form::invalid-input', error.getPayload()); // @todo implement handler for this event
            } else {
                // noinspection JSIgnoredPromiseFromCall
                swal({
                    title: 'Cannot save page',
                    text: error.toString(),
                    icon: 'error',
                });
            }
        } finally {
            this.bus.emit('form::submitted', { response });
        }
    }

    private serialize() {
        const { attachments, notes, page } = this.state.sections;

        return Object.assign(page.serialize(), {
            attachment_ids: attachments.serialize(),
            notes: notes.serialize(),
        });
    }

}

