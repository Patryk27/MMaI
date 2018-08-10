import CreateTagModalComponent from './index/CreateTagModalComponent';
import SearchFormComponent from './index/SearchFormComponent';
import SearchResultsComponent from './index/SearchResultsComponent';

export default function () {
    let currentLanguageId = null;

    const
        createTagModal = new CreateTagModalComponent('#create-tag-modal'),
        searchResults = new SearchResultsComponent('#tags-loader', '#tags-table'),
        searchForm = new SearchFormComponent('#tags-search-form');

    $('#create-tag-button').on('click', () => {
        createTagModal.show(currentLanguageId);
    });

    searchForm.onLanguageChanged((languageId) => {
        currentLanguageId = languageId;
    });

    searchForm.onSubmit((form) => {
        searchResults.refresh(form);
    });

    createTagModal.show(1);
};