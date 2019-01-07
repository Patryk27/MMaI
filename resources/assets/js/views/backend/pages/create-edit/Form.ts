import { PagesFacade } from '../../../../api/pages/PagesFacade';
import { AttachmentsSection } from './sections/AttachmentsSection';
import { NotesSection } from './sections/NotesSection';
import { PageSection } from './sections/PageSection';

type SubmittingEventHandler = () => void;
type SubmittedEventHandler = () => void;
type ErrorEventHandler = () => void;

export class Form {

    private readonly form: JQuery;

    private readonly attachmentsSection: AttachmentsSection;
    private readonly notesSection: NotesSection;
    private readonly pageSection: PageSection;

    private eventHandlers: {
        submitting?: SubmittingEventHandler,
        submitted?: SubmittedEventHandler,
        error?: ErrorEventHandler,
    } = {};

    private invalidated: boolean = false;

    constructor(
        form: JQuery,
        attachmentsSection: AttachmentsSection,
        notesSection: NotesSection,
        pageSection: PageSection,
    ) {
        this.form = form;

        this.attachmentsSection = attachmentsSection;
        this.notesSection = notesSection;
        this.pageSection = pageSection;
    }

    public onSubmitting(fn: SubmittingEventHandler): void {
        this.eventHandlers.submitting = fn;
    }

    public onSubmitted(fn: SubmittedEventHandler): void {
        this.eventHandlers.submitted = fn;
    }

    public onError(fn: ErrorEventHandler): void {
        this.eventHandlers.error = fn;
    }

    public invalidate(): void {
        this.invalidated = true;
    }

    public isInvalidated(): boolean {
        return this.invalidated;
    }

    public async submit(): Promise<void> {
        try {
            const request = Object.assign(this.pageSection.serialize(), {
                attachmentIds: this.attachmentsSection.serialize(),
                notes: this.notesSection.serialize(),
            });

            let response;

            if (this.form.data('action') === 'create') {
                response = await PagesFacade.create(request);
            } else {
                response = await PagesFacade.update(this.form.data('id'), request);
            }

            console.log(response);

            this.invalidated = false;
        } catch (error) {
            // if (error.getType && error.getType() === 'invalid-input') {
            //     this.bus.emit('form::invalid-input', error.getPayload()); // @todo implement handler for this event
            // } else {
            //     // noinspection JSIgnoredPromiseFromCall
            //     swal({
            //         title: 'Cannot save page',
            //         text: error.toString(),
            //         icon: 'error',
            //     });
            // }
        } finally {
            // this.bus.emit('form::submitted', { response });
        }
    }

}

