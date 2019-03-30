import { AttachmentsSection } from '@/modules/pages/form/AttachmentsSection';
import { NotesSection } from '@/modules/pages/form/NotesSection';
import { PageSection } from '@/modules/pages/form/PageSection';
import { PagesFacade } from '@/modules/pages/PagesFacade';
import { Form } from '@/ui/form';
import { EventBus } from '@/utils/EventBus';

export class PageForm {

    private readonly bus: EventBus;
    private readonly form: Form;

    public constructor() {
        this.bus = new EventBus();

        this.form = new Form({
            controls: [
                new AttachmentsSection(this.bus),
                new NotesSection(),
                new PageSection(),
            ],
        });
    }

    public async submit(): Promise<{ redirectTo: string } | null> {
        this.form.clearErrors();

        try {
            const page = this.form.serialize();

            if (page.id) {
                return await PagesFacade.updatePage(page.id, page);
            } else {
                return await PagesFacade.createPage(page);
            }
        } catch (error) {
            if (error.formErrors) {
                this.form.addErrors(error.formErrors);
            } else {
                throw error;
            }
        }

        return null;
    }

}
