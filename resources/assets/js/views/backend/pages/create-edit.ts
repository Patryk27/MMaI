import { app } from '@/Application';
import { Button, Overlay } from '@/ui/components';
import { Form } from '@/views/backend/pages/create-edit/Form';

class View {

    private readonly form: Form;
    private readonly submitBtn: Button;
    private readonly overlay: Overlay;

    constructor() {
        this.form = new Form();

        this.submitBtn = new Button($('#form-submit'));
        this.submitBtn.on('click', () => {
            // noinspection JSIgnoredPromiseFromCall
            this.submit();
        });

        this.overlay = new Overlay();
    }

    private async submit() {
        this.overlay.show();
        this.submitBtn.disable();
        this.submitBtn.showSpinner();

        this.form.submit().then(() => {
            this.overlay.hide();
            this.submitBtn.enable();
            this.submitBtn.hideSpinner();
        });
    }

}

app.addViewInitializer('backend.pages.create', () => {
    new View();
});

app.addViewInitializer('backend.pages.edit', () => {
    new View();
});
