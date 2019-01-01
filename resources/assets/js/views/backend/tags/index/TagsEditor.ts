import swal from 'sweetalert';
import { Tag } from '../../../../api/tags/Tag';
import { TagsFacade } from '../../../../api/tags/TagsFacade';
import { Button } from '../../../../components/Button';
import { Input } from '../../../../components/Input';
import { EventBus } from '../../../../utils/EventBus';

export class TagsEditor {

    private readonly bus: EventBus;

    private readonly dom: {
        modal: JQuery,
        form: JQuery,
    };

    private readonly form: {
        name: Input,
    };

    private readonly formMapping: any;

    private readonly buttons: {
        close: Button,
        submit: Button,
    };

    private readonly state: {
        tag?: Tag,
    };

    constructor(bus: EventBus, modal: JQuery) {
        this.bus = bus;

        this.dom = {
            modal,
            form: modal.find('form'),
        };

        this.form = {
            name: new Input(modal.find('[name="name"]')),
        };

        // @todo rename this field + reduce DRY in these modals
        this.formMapping = {
            name: 'name',
        };

        this.buttons = {
            close: new Button(modal.find('.btn-close')),
            submit: new Button(modal.find('.btn-submit')),
        };

        this.state = {
            tag: undefined,
        };

        this.dom.modal.on('shown.bs.modal', () => {
            this.form.name.focus();
        });

        this.dom.form.on('submit', () => {
            // noinspection JSIgnoredPromiseFromCall
            this.submit();
            return false;
        });
    }

    public run(tag: Tag): void {
        this.state.tag = tag;
        this.form.name.setValue(tag.name);

        // @ts-ignore
        this.dom.modal.modal();
    }

    private close(): void {
        // @ts-ignore
        this.dom.modal.modal('hide');
    }

    private changeState(state: string): void {
        const buttons = this.buttons;

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

            await TagsFacade.update(this.state.tag.id, {
                name: form.name.getValue(),
            });

            this.bus.emit('tag::updated');

            await swal({
                title: 'Success',
                text: 'Tag has been updated.',
                icon: 'success',
            });

            this.close();
        } catch (error) {
            if (error.getType && error.getType() === 'invalid-input') {
                // for (const [fieldName, [fieldError]] of Object.entries(error.getPayload())) {
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
