import swal from 'sweetalert';
import { app } from '../../../Application';
import { Button } from '../../../components/Button';
import { Overlay } from '../../../components/Overlay';
import { EventBus } from '../../../utils/EventBus';
import { Form } from './create-edit/Form';
import { AttachmentsSection } from './create-edit/sections/AttachmentsSection';
import { NotesSection } from './create-edit/sections/NotesSection';
import { PageSection } from './create-edit/sections/PageSection';

class View {

    private readonly bus: EventBus;

    private readonly dom: {
        form: JQuery,
        submitButton: JQuery,
        sectionTabs: JQuery,
        sectionContents: JQuery,
    };

    private state: {
        sections: {
            attachments: AttachmentsSection,
            notes: NotesSection,
            page: PageSection,
        },
    };

    private overlay: Overlay;
    private submitButton: Button;
    private form: Form;

    constructor() {
        this.bus = new EventBus();
        this.overlay = new Overlay();

        this.dom = {
            form: $('#page-form'),
            submitButton: $('#page-form-submit-button'),

            sectionTabs: $('#page-section-tabs'),
            sectionContents: $('#page-section-contents'),
        };

        this.initializeSection();
        this.initializeForm();
        this.registerOnBeforeUnloadHandler();
        this.registerSaveShortcut();

        // Focus on the first available tab
        this.dom.sectionTabs.find('.nav-link').eq(0).click();
    }

    private initializeSection(): void {
        const sections: {
            attachments?: AttachmentsSection,
            notes?: NotesSection,
            page?: PageSection
        } = {};

        this.dom.sectionTabs.on('click', '.nav-link', (evt) => {
            setTimeout(() => {
                this.bus.emit('tabs::changed', {
                    currentSection: $(evt.target).attr('href').substring(1),
                });
            });
        });

        this.dom.sectionContents.find('.tab-pane').each((_, tabElement) => {
            const tab = $(tabElement);

            switch (tab.data('section-type')) {
                case 'attachments':
                    sections.attachments = new AttachmentsSection(this.bus, tab);
                    break;

                case 'notes':
                    sections.notes = new NotesSection(this.bus, tab);
                    break;

                case 'page':
                    sections.page = new PageSection(this.bus, tab);
                    break;

                default:
                    throw 'Unknown section [' + tab.data('section-type') + '].';
            }
        });

        if (!sections.attachments || !sections.notes || !sections.page) {
            throw 'Failed to initialize the page.';
        }

        this.state = {
            // @ts-ignore
            sections,
        };
    }

    private initializeForm(): void {
        this.submitButton = new Button(this.dom.submitButton);
        this.submitButton.disable();
        this.submitButton.on('click', () => this.form.submit());

        this.form = new Form(this.bus, this.dom.form, this.state.sections);

        this.bus.on('form::changed', () => {
            if (!document.title.includes('* ')) {
                document.title = '* ' + document.title;
            }

            this.form.makeDirty();
            this.submitButton.enable();
        });

        this.bus.on('form::submit', () => this.form.submit());

        this.bus.on('form::submitting', () => {
            this.overlay.show();
            this.submitButton.disable();
            this.submitButton.showSpinner();
        });

        this.bus.on('form::submitted', ({ response }) => {
            this.overlay.hide();
            this.submitButton.enable();
            this.submitButton.hideSpinner();

            if (response) {
                swal({
                    title: 'Success',
                    text: 'Page has been saved.',
                    icon: 'success',
                }).then(() => {
                    // noinspection JSUnresolvedVariable
                    window.location.href = response.data.redirectTo;
                });
            }
        });
    }

    /**
     * Registers a handler that listens for the Ctrl+S keystroke and then submits the form.
     */
    private registerSaveShortcut(): void {
        $(window).on('keypress', (event) => {
            if (event.which === 115 && event.ctrlKey) {
                // It may happen that user presses Ctrl+S while some input is still focused - this can cause a situation
                // where input is blurred *after* the form has been saved, which will - in turn - mark form as dirty
                // after it's been submitted. That's hardly desirable and so we have to prevent it right now.
                $('*').blur();

                // Having everything prepared, we can safely submit the form.
                this.bus.emit('form::submit');

                // The default browser's behavior for Ctrl+S is to save the page's contents - that's not what we are
                // trying to accomplish though, so let's prevent this.
                event.stopPropagation();

                return false;
            }
        });
    }

    /**
     * Registers a handler that forbids closing the window when there are unsaved changes.
     */
    private registerOnBeforeUnloadHandler(): void {
        window.onbeforeunload = (event) => {
            if (this.form.isDirty()) {
                event.preventDefault();
                event.returnValue = '';
            }
        };
    }

}

app.addViewInitializer('backend.pages.create', () => {
    new View();
});

app.addViewInitializer('backend.pages.edit', () => {
    new View();
});
