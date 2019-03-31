import { Form } from '@/modules/core/Form';
import { Button, Modal } from '@/ui/components';

export class FormModal<E, F extends Form<E>> {

    private readonly form: F;
    private readonly modal: Modal;
    private readonly buttons: { close: Button, submit: Button };

    private eventHandlers: {
        resolve: (entity: E) => void,
        reject: () => void,
    };

    public constructor(modal: JQuery, form: F) {
        this.form = form;

        this.modal = new Modal(modal);
        this.modal.on('submit', () => {
            this.submit().catch(window.onerror);
            return false;
        });

        this.buttons = {
            close: new Button(modal.find('.btn-close')),
            submit: new Button(modal.find('.btn-submit')),
        };
    }

    public show(entity?: E): Promise<E> {
        this.form.reset(entity);
        this.modal.show();

        return new Promise((resolve, reject) => {
            this.eventHandlers = { resolve, reject };
        });
    }

    private async submit(): Promise<void> {
        this.buttons.close.disable();
        this.buttons.submit.disable();
        this.buttons.submit.showSpinner();

        const entity = await this.form.submit();

        if (entity) {
            this.modal.hide();
            this.eventHandlers.resolve(entity);
        }

        this.buttons.close.enable();
        this.buttons.submit.enable();
        this.buttons.submit.hideSpinner();
    }

}
