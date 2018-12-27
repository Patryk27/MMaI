import swal from 'sweetalert';

import AttachmentsSection from './create-edit/sections/AttachmentsSection';
import EventBus from '../../../EventBus';
import ButtonComponent from '../../../components/ButtonComponent';
import Form from './create-edit/Form';
import NotesSection from './create-edit/sections/NotesSection';
import OverlayComponent from '../../../components/OverlayComponent';
import PageSection from './create-edit/sections/PageSection';
import app from '../../../Application';

class View {

    constructor() {
        this.$bus = new EventBus();
        this.$overlay = new OverlayComponent();

        this.$dom = {
            form: $('#page-form'),
            submitButton: $('#page-form-submit-button'),

            sectionTabs: $('#page-section-tabs'),
            sectionContents: $('#page-section-contents'),
        };

        this.$state = {
            sections: {
                pages: [],
                attachments: null,
            },
        };

        this.$initializeSections();
        this.$initializeForm();
        this.$registerOnBeforeUnloadHandler();
        this.$registerSaveShortcut();

        // After everything's loaded, let's open the first available tab
        this.$dom.sectionTabs.find('.nav-link').eq(0).click();
    }

    /**
     * @private
     */
    $initializeSections() {
        const sections = this.$state.sections;

        this.$dom.sectionTabs.on('click', '.nav-link', (evt) => {
            setTimeout(() => {
                this.$bus.emit('tabs::changed', {
                    activatedTabName: $(evt.target).attr('href').substring(1),
                });
            });
        });

        this.$dom.sectionContents.find('.tab-pane').each((_, tab) => {
            const $tab = $(tab);

            switch ($tab.data('section-type')) {
                case 'attachments':
                    sections.attachments = new AttachmentsSection(this.$bus, $tab);
                    break;

                case 'notes':
                    sections.notes = new NotesSection(this.$bus, $tab);
                    break;

                case 'page':
                    sections.page = new PageSection(this.$bus, $tab);
                    break;

                default:
                    throw 'Unknown section [' + $tab.data('section-type') + '].';
            }
        });
    }

    /**
     * @private
     */
    $initializeForm() {
        this.$submitButton = new ButtonComponent(this.$dom.submitButton);
        this.$submitButton.disable();
        this.$submitButton.on('click', () => this.$form.submit());

        this.$form = new Form(this.$bus, this.$dom.form, this.$state.sections);

        this.$bus.on('form::changed', () => {
            if (!document.title.includes('* ')) {
                document.title = '* ' + document.title;
            }

            this.$form.markAsDirty();
            this.$submitButton.enable();
        });

        this.$bus.on('form::submit', () => this.$form.submit());

        this.$bus.on('form::submitting', () => {
            this.$overlay.show();
            this.$submitButton.disable();
            this.$submitButton.showSpinner();
        });

        this.$bus.on('form::submitted', ({ response }) => {
            this.$overlay.hide();
            this.$submitButton.enable();
            this.$submitButton.hideSpinner();

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
     * @private
     *
     * Registers a handler that listens for the Ctrl+S keystroke and then submits the form.
     */
    $registerSaveShortcut() {
        $(window).on('keypress', (event) => {
            if (event.which === 115 && event.ctrlKey) {
                // It may happen that user presses Ctrl+S while some input is still focused - this can cause a situation
                // where input is blurred *after* the form has been saved, which will - in turn - mark form as dirty
                // after it's been submitted. That's hardly desirable and so we have to prevent it right now.
                $('*').blur();

                // Having everything prepared, we can safely submit the form.
                this.$bus.emit('form::submit');

                // The default browser's behavior for Ctrl+S is to save the page's contents - that's not what we are
                // trying to accomplish though, so let's prevent this.
                event.stopPropagation();
                return false;
            }
        });
    }

    /**
     * @private
     *
     * Registers a handler that forbids closing the window when there are unsaved changes.
     */
    $registerOnBeforeUnloadHandler() {
        window.onbeforeunload = (event) => {
            if (this.$form.isDirty()) {
                event.preventDefault();
                event.returnValue = '';
            }
        };
    }

}

app.registerView('backend.pages.create', () => {
    new View();
});

app.registerView('backend.pages.edit', () => {
    new View();
});
