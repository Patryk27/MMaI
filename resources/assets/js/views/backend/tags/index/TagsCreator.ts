import swal from 'sweetalert';
import { TagsFacade } from '../../../../api/tags/TagsFacade';
import { Button } from '../../../../components/Button';
import { Input } from '../../../../components/Input';
import { EventBus } from '../../../../utils/EventBus';

export class TagsCreator {

    private readonly bus: EventBus;

    private readonly dom: {
        modal: JQuery,
        form: JQuery,
    };

    private readonly form: {
        name: Input,
        websiteId: Input,
    };

    private readonly formMapping: any;

    private readonly button: {
        close: Button,
        submit: Button,
    };

    constructor(bus: EventBus, modal: JQuery) {
        this.bus = bus;

        this.dom = {
            modal,
            form: modal.find('form'),
        };

        this.form = {
            name: new Input(modal.find('[name="name"]')),
            websiteId: new Input(modal.find('[name="website_id"]')),
        };

        // @todo rename this field + reduce DRY in these modals
        this.formMapping = {
            name: 'name',
            website_id: 'websiteId',
        };

        this.button = {
            close: new Button(modal.find('.btn-close')),
            submit: new Button(modal.find('.btn-submit')),
        };

        this.dom.modal.on('shown.bs.modal', () => {
            this.form.name.setValue('');
            this.form.name.focus();
        });

        this.dom.form.on('submit', () => {
            // noinspection JSIgnoredPromiseFromCall
            this.submit();
            return false;
        });
    }

    public setWebsiteId(websiteId: number): void {
        this.form.websiteId.setValue(websiteId);
    }

    public run(): void {
        // @ts-ignore
        this.dom.modal.modal();
    }

    private close(): void {
        // @ts-ignore
        this.dom.modal.modal('hide');
    }

    private changeState(state: string): void {
        const buttons = this.button;

        switch (state) {
            case 'submitting':
                buttons.close.disable();

                buttons.submit.disable();
                buttons.submit.showSpinner();

                break;

            case 'ready':
                buttons.close.enable();

                buttons.submit.enable();
                buttons.submit.hideSpinner();

                break;
        }
    }

    private async submit(): Promise<void> {
        this.changeState('submitting');

        const form = this.form;

        try {
            for (const [, component] of Object.entries(form)) {
                component.removeFeedback();
            }

            await TagsFacade.create({
                name: form.name.getValue(),
                websiteId: form.websiteId.getValue(),
            });

            this.bus.emit('tag::created');

            await swal({
                title: 'Success',
                text: 'Tag has been created.',
                icon: 'success',
            });

            this.close();
        } catch (error) {
            if (error.getType && error.getType() === 'invalid-input') {
                // for (const [fieldName, [fieldError]] of Object.entries(error.payload)) {
                //     const formFieldName = this.formMapping[fieldName];
                //     const component = this.$form[formFieldName];
                //
                //     component.setFeedback('invalid', fieldError);
                // }
            } else {
                swal({
                    title: 'Cannot create tag',
                    text: error.toString(),
                    icon: 'error',
                }).then(() => {
                    form.name.focus();
                });
            }
        }

        this.changeState('ready');
    }

}
