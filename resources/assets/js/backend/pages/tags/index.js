import Bus from '../../../base/Bus';
import CreateTagModalComponent from './index/CreateTagModalComponent';
import SearchFormComponent from './index/SearchFormComponent';
import SearchResultsComponent from './index/SearchResultsComponent';

export default function () {
    const bus = new Bus();

    // noinspection JSUnusedLocalSymbols
    const
        createTagModal = new CreateTagModalComponent(bus, '#create-tag-modal'),
        searchForm = new SearchFormComponent(bus, '#tags-search-form'),
        searchResults = new SearchResultsComponent(bus, '#tags-loader', '#tags-table');

    // When user clicks on the "create tag" button, open the "create tag" modal
    $('#create-tag-button').on('click', () => {
        createTagModal.open();
    });

    // When a new tag is created, refresh the search results, so that they can include it
    bus.on('tag::created', () => {
        searchForm.submit();
    });

    // When the search form is being submitted, refresh the search results
    bus.on('search-form::submit', (form) => {
        createTagModal.setLanguageId(form.languageId);
        searchResults.refresh(form);
    });

    searchForm.submit();
};