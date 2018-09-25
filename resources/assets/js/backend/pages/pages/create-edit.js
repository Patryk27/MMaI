import swal from 'sweetalert';

import AttachmentsSection from './create-edit/sections/AttachmentsSection';
import Bus from '../../../base/Bus';
import ButtonComponent from '../../../base/components/ButtonComponent';
import FormSubmitter from './create-edit/FormSubmitter';
import NotesSection from './create-edit/sections/NotesSection';
import PageVariantSection from './create-edit/sections/PageVariantSection';

export default function () {
    const bus = new Bus();

    const dom = {
        form: $('#page-form'),
        formSubmitButton: $('#page-form-submit-button'),

        sectionTabs: $('#page-section-tabs'),
        sectionContents: $('#page-section-contents'),
    };

    const formSections = {
        pageVariants: [],
        attachments: null,
    };

    // When user changes a tab, let's emit a `tabs changed` to let components know that they might have to re-render.
    // Precisely: SimpleMDE has to be manually refreshed when its state changes from `hidden` to `visible` - otherwise
    // it glitches out.
    dom.sectionTabs.on('click', '.nav-link', (evt) => {
        setTimeout(() => {
            bus.emit('tabs::changed', {
                activatedTabName: $(evt.target).attr('href').substring(1),
            });
        });
    });

    dom.sectionContents.find('.tab-pane').each((_, tab) => {
        const $tab = $(tab);

        switch ($tab.data('section-type')) {
            case 'attachments':
                formSections.attachments = new AttachmentsSection(bus, $tab);

                break;

            case 'notes':
                formSections.notes = new NotesSection(bus, $tab);

                break;

            case 'page-variant':
                formSections.pageVariants.push(
                    new PageVariantSection(bus, $tab),
                );

                break;

            default:
                throw 'Do not know how to handle section [type=' + $tab.data('section-type') + ']';
        }
    });

    const
        formSubmitButton = new ButtonComponent(dom.formSubmitButton),
        formSubmitter = new FormSubmitter(bus, dom.form, formSections);

    bus.on('form::submit', () => {
        // noinspection JSIgnoredPromiseFromCall
        formSubmitter.submit();
    });

    bus.on('form::submitting', () => {
        formSubmitButton.disable();
        formSubmitButton.showSpinner();
    });

    bus.on('form::submitted', ({ response }) => {
        formSubmitButton.enable();
        formSubmitButton.hideSpinner();

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

    dom.formSubmitButton.on('click', () => {
        // noinspection JSIgnoredPromiseFromCall
        formSubmitter.submit();
    });

    // Activate the first available tab
    dom.sectionTabs.find('.nav-link').eq(0).click();
};
