import { PagesFacade } from '@/api/pages/PagesFacade';
import { app } from '@/Application';
import { Button, Overlay } from '@/ui/components';
import { Form } from '@/ui/form';
import { EventBus } from '@/utils/EventBus';
import { AttachmentsSection } from '@/views/backend/pages/create-edit/AttachmentsSection';
import { NotesSection } from '@/views/backend/pages/create-edit/NotesSection';
import { PageSection } from '@/views/backend/pages/create-edit/PageSection';

class View {

    private readonly bus: EventBus;
    private readonly form: Form;
    private readonly submitBtn: Button;
    private readonly overlay: Overlay;

    constructor() {
        this.bus = new EventBus();

        this.form = new Form({
            controls: [
                new AttachmentsSection(this.bus),
                new NotesSection(),
                new PageSection(),
            ],
        });

        this.submitBtn = new Button($('#form-submit'));
        this.submitBtn.on('click', () => {
            this.submit().catch(window.onerror);
        });

        this.overlay = new Overlay();

        // $('#form').on('change', () => {
        //     this.dirty = true;
        // });
    }

    private async submit() {
        this.form.clearErrors();

        this.overlay.show();
        this.submitBtn.disable();
        this.submitBtn.showSpinner();

        try {
            let request = this.form.serialize();
            let response;

            if (request.id) {
                response = await PagesFacade.update(request.id, request);
            } else {
                response = await PagesFacade.create(request);
            }

            window.location.href = response.redirectTo;
        } catch (error) {
            if (error.type === 'exception') {
                error.display('Failed to save the page');
            }

            if (error.formErrors) {
                this.form.addErrors(error.formErrors);
            }

            this.overlay.hide();
            this.submitBtn.enable();
            this.submitBtn.hideSpinner();
        }
    }

}

app.onViewReady('backend.pages.create', () => {
    new View();
});

app.onViewReady('backend.pages.edit', () => {
    new View();
});
