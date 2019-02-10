import { AttachmentsSection } from '@/components/pages/form/AttachmentsSection';
import { NotesSection } from '@/components/pages/form/NotesSection';
import { PageSection } from '@/components/pages/form/PageSection';
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

    public async submit(): Promise<object | null> {
        this.form.clearErrors();

        try {
            console.log('@todo');
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
