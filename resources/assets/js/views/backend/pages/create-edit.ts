import { app } from '@/Application';
import { PageForm } from '@/modules/pages/PageForm';
import { Button, Overlay } from '@/ui/components';

function run() {
    const pageForm = new PageForm();
    const submitButton = new Button($('#form-submit'));
    const overlay = new Overlay();

    submitButton.on('click', () => {
        overlay.show();
        submitButton.disable();
        submitButton.showSpinner();

        pageForm.submit()
            .then((result) => {
                if (result && result.redirectTo) {
                    window.location.href = result.redirectTo;
                } else {
                    overlay.hide();
                    submitButton.enable();
                    submitButton.hideSpinner();
                }
            })
            .catch(window.onerror);
    });
}

app.onViewReady('backend.pages.create', run);
app.onViewReady('backend.pages.edit', run);
