import swal from 'sweetalert';

import Bus from '../../../base/Bus';
import ButtonComponent from '../../../base/components/ButtonComponent';
import FormSubmitter from './create-edit/FormSubmitter';
import AttachmentsComponent from './create-edit/components/AttachmentsComponent';
import PageVariantComponent from './create-edit/components/PageVariantComponent';

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

    dom.sectionTabs.on('click', '.nav-link', () => {
        // Bootstrap handles switching the tabs on its own - we do not have to touch DOM here.
        // Since it happens in the next tick, that is also the reason we are emitting "tabs changed" event then.
        setTimeout(() => {
            bus.emit('tabs::changed');
        });
    });

    dom.sectionContents.find('.tab-pane').each((_, tab) => {
        const $tab = $(tab);

        switch ($tab.data('section-type')) {
            case 'page-variant':
                formSections.pageVariants.push(
                    new PageVariantComponent(bus, $tab),
                );

                break;

            case 'attachments':
                formSections.attachments = new AttachmentsComponent(bus, $tab);

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
