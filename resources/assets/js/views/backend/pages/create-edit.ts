import swal from 'sweetalert';
import { app } from '../../../Application';
import { Button, Overlay } from '../../../ui/components';
import { EventBus } from '../../../utils/EventBus';
import { Form } from './create-edit/Form';
import { AttachmentsSection } from './create-edit/sections/AttachmentsSection';
import { NotesSection } from './create-edit/sections/NotesSection';
import { PageSection } from './create-edit/sections/PageSection';

class View {

    private readonly bus: EventBus;

    private readonly dom: {
        form: JQuery,
        submit: JQuery,
        sectionTabs: JQuery,
        sectionContents: JQuery,
    };

    private overlay: Overlay;

    private attachmentsSection: AttachmentsSection;
    private notesSection: NotesSection;
    private pageSection: PageSection;

    private form: Form;
    private submit: Button;

    constructor() {
        this.bus = new EventBus();
        this.overlay = new Overlay();

        this.dom = {
            form: $('#page-form'),
            submit: $('#page-form-submit-button'),
            sectionTabs: $('#page-section-tabs'),
            sectionContents: $('#page-section-contents'),
        };

        this.initializeSections();
        this.initializeSubmit();
        this.initializeForm();
        this.registerOnBeforeUnloadHandler();
        this.registerSaveShortcut();

        // Focus on the first available tab
        this.dom.sectionTabs.find('.nav-link').eq(0).click();
    }

    private initializeSections(): void {
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
                    this.attachmentsSection = new AttachmentsSection(this.bus, tab);
                    break;

                case 'notes':
                    this.notesSection = new NotesSection(this.bus, tab);
                    break;

                case 'page':
                    this.pageSection = new PageSection(this.bus, tab);
                    break;

                default:
                    throw 'Unknown section: ' + tab.data('section-type');
            }
        });
    }

    private initializeSubmit(): void {
        this.submit = new Button(this.dom.submit);
        this.submit.disable();
        this.submit.on('click', () => this.form.submit());
    }

    private initializeForm(): void {
        this.form = new Form(
            this.dom.form,
            this.attachmentsSection,
            this.notesSection,
            this.pageSection,
        );

        this.form.onSubmitting(() => {

        });

        this.form.onSubmitted(() => {

        });

        this.form.onError(() => {

        });

        this.bus.on('form::invalidate', () => {
            if (!this.form.isInvalidated()) {
                document.title = '* ' + document.title;
            }

            this.form.invalidate();
            this.submit.enable();
        });

        this.bus.on('form::submit', () => {
            // noinspection JSIgnoredPromiseFromCall
            this.form.submit();
        });

        this.bus.on('form::submitting', () => {
            this.overlay.show();
            this.submit.disable();
            this.submit.showSpinner();
        });

        this.bus.on('form::submitted', ({ response }) => {
            this.overlay.hide();
            this.submit.enable();
            this.submit.hideSpinner();

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
            if (this.form.isInvalidated()) {
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
