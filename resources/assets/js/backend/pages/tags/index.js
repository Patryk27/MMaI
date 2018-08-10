import CreateTagModalComponent from './index/CreateTagModalComponent';
import DataTableComponent from './index/DataTableComponent';
import SearchFormComponent from './index/SearchFormComponent';

export default function () {
    let currentLanguageId = null;

    const
        createTagModal = new CreateTagModalComponent('#create-tag-modal'),
        dataTable = new DataTableComponent('#tags-loader', '#tags-table'),
        searchForm = new SearchFormComponent('#tags-search-form');

    $('#create-tag-button').on('click', () => {
        createTagModal.show(currentLanguageId);
    });

    searchForm.onLanguageChanged((languageId) => {
        currentLanguageId = languageId;
    });

    searchForm.onSubmit((form) => {
        dataTable.refresh(form);
    });

    createTagModal.show(1);
};