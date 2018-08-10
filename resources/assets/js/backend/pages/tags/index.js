import Bus from '../../../base/Bus';
import CreateTagModalComponent from './index/CreateTagModalComponent';
import SearchFormComponent from './index/SearchFormComponent';
import SearchResultsComponent from './index/SearchResultsComponent';

export default function () {
    const bus = new Bus();

    // noinspection JSUnusedLocalSymbols
    const
        createTagModal = new CreateTagModalComponent('#create-tag-modal'),
        searchResults = new SearchResultsComponent(bus, '#tags-loader', '#tags-table'),
        searchForm = new SearchFormComponent(bus, '#tags-search-form');

    $('#create-tag-button').on('click', () => {
        createTagModal.show();
    });

    bus.on('search-form::submit', (form) => {
        createTagModal.setLanguageId(form.languageId);
        searchResults.refresh(form);
    });
};