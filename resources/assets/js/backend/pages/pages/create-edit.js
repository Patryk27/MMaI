import $ from 'jquery';

import MessageBag from '../../../base/components/MessageBag';

import MediaLibrarySection from './create-edit/sections/MediaLibrary';
import PageVariant from './create-edit/sections/PageVariant';
import Submitter from './create-edit/Submitter';

/**
 * Initializes the form's message bag.
 *
 * @param {jQuery} $form
 * @return {MessageBag}
 */
function initializeMessageBag($form) {
    return new MessageBag(
        $form.find('.form-messages'),
    );
}

/**
 * Initializes the form's sections (tabs).
 *
 * @param {jQuery} $form
 * @return {object}
 */
function initializeSections($form) {
    // List of all the sections, divided by type
    const sections = {
        mediaLibrary: {},
        pageVariants: [],
    };

    /**
     * When user opens a section, we're calling the refresh method on it - it helps to overcome any bugs related to
     * section (tab) being shown out of blue (e.g. with SimpleMDE).
     */
    $form.on('click', '.form-navigation-tabs .nav-link', function () {
        const $sectionHeader = $(this);

        // Fetch the section to which this link refers to
        const $section = $(
            $sectionHeader.attr('href'),
        );

        // Fetch the section's handler
        const section = $section.data('section');

        // And if that handler contains the refresh method, call it
        if (section && section.refresh) {
            section.refresh();
        }
    });

    /**
     * Instantiate handlers for each section present in the DOM.
     * These handlers are responsible for serializing section's data before submitting them and other state-related
     * things.
     */
    $form.find('.form-navigation-contents .tab-pane').each(function () {
        const $section = $(this);

        switch ($section.data('type')) {
            case 'media-library': {
                // Instantiate the media library section's handler
                const section = new MediaLibrarySection($section);

                // Save it into the section's DOM, so that we can re-use it later
                $section.data('section', section);

                // Save it for the function's result
                sections.mediaLibrary = section;

                break;
            }

            case 'page-variant': {
                // Instantiate the page variant section's handler
                const section = new PageVariant($section);

                // Save it into the section's DOM, so that we can re-use it later
                $section.data('section', section);

                // Save it for the function's result
                sections.pageVariants.push(section);

                break;
            }

            default:
                console.error('Unknown section\'s type: ' + $section.data('type'));
        }
    });

    return sections;
}

export default function () {
    const
        $form = $('#form'),
        messageBag = initializeMessageBag($form),
        sections = initializeSections($form);

    new Submitter($form, messageBag, sections);

    // Activate the first available tab (e.g. the English page variant)
    $form.find('.form-navigation-tabs .nav-link').eq(0).click();
};