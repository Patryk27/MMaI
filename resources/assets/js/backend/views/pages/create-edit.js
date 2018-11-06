import swal from 'sweetalert';

import AttachmentsSection from './create-edit/sections/AttachmentsSection';
import Bus from '../../../base/Bus';
import ButtonComponent from '../../../base/components/ButtonComponent';
import Form from './create-edit/Form';
import NotesSection from './create-edit/sections/NotesSection';
import OverlayComponent from '../../../base/components/OverlayComponent';
import PageVariantSection from './create-edit/sections/PageVariantSection';

class View {

    constructor() {
        this.$bus = new Bus();
        this.$overlay = new OverlayComponent();

        this.$dom = {
            form: $('#page-form'),
            formSubmitButton: $('#page-form-submit-button'),

            sectionTabs: $('#page-section-tabs'),
            sectionContents: $('#page-section-contents'),
        };

        this.$state = {
            formSections: {
                pageVariants: [],
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
        const formSections = this.$state.formSections;

        // When user changes a tab, let's emit a `tabs changed` event to let components know that they might have to
        // re-render. Precisely: SimpleMDE has to be manually refreshed when its state changes from `hidden` to
        // `visible` - otherwise it glitches out.
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
                    formSections.attachments = new AttachmentsSection(this.$bus, $tab);

                    break;

                case 'notes':
                    formSections.notes = new NotesSection(this.$bus, $tab);

                    break;

                case 'page-variant':
                    formSections.pageVariants.push(
                        new PageVariantSection(this.$bus, $tab),
                    );

                    break;

                default:
                    throw 'Do not know how to handle section with type [' + $tab.data('section-type') + ']';
            }
        });
    }

    /**
     * @private
     */
    $initializeForm() {
        // Register the "submit" button and make it disabled by the default.
        // We'll enable it right after user does some change to the form.
        this.$formSubmitButton = new ButtonComponent(this.$dom.formSubmitButton);
        this.$formSubmitButton.disable();
        this.$formSubmitButton.on('click', () => {
            // noinspection JSIgnoredPromiseFromCall
            this.$form.submit();
        });

        // Create instance of the form which will handle all those funky form-serializing-things for us
        this.$form = new Form(this.$bus, this.$dom.form, this.$state.formSections);

        this.$bus.on('form::changed', () => {
            // If form has just been made dirty, add the asterisk to the document's title; purely for user's feedback.
            if (!this.$form.isDirty()) {
                document.title = '* ' + document.title;
            }

            this.$form.markAsDirty();
            this.$formSubmitButton.enable();
        });

        this.$bus.on('form::submit', () => {
            // noinspection JSIgnoredPromiseFromCall
            this.$form.submit();
        });

        this.$bus.on('form::submitting', () => {
            this.$overlay.show();
            this.$formSubmitButton.disable();
            this.$formSubmitButton.showSpinner();
        });

        this.$bus.on('form::submitted', ({ response }) => {
            this.$overlay.hide();
            this.$formSubmitButton.enable();
            this.$formSubmitButton.hideSpinner();

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

export default function () {
    new View();
};
