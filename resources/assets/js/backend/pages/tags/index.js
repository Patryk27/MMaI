import Bus from '../../../base/Bus';
import CreateTagComponent from './index/CreateTagComponent';
import DeleteTagComponent from './index/DeleteTagComponent';
import SearchFormComponent from './index/SearchFormComponent';
import SearchResultsComponent from './index/SearchResultsComponent';

export default function () {
    const bus = new Bus();

    // noinspection JSUnusedLocalSymbols
    const
        createTag = new CreateTagComponent(bus, '#create-tag-modal'),
        deleteTag = new DeleteTagComponent(bus);

    const
        searchForm = new SearchFormComponent(bus, '#tags-search-form'),
        searchResults = new SearchResultsComponent(bus, '#tags-loader', '#tags-table');

    // When user clicks on the "create tag" button, open the "create tag" modal
    $('#create-tag-button').on('click', () => {
        createTag.open();
    });

    // When user clicks on the "edit tag" / "delete tag" buttons, execute appropriate action
    $('#tags-table').on('click', '.btn-tag-action', (evt) => {
        const
            $target = $(evt.currentTarget),
            tag = $target.data('tag');

        switch ($target.data('action')) {
            case 'edit':
                break;

            case 'delete':
                // noinspection JSIgnoredPromiseFromCall
                deleteTag.deleteTag(tag);
                break;
        }
    });

    // When a new tag is created or modified, refresh the search results
    bus.onMany(['tag::created', 'tag::modified', 'tag::deleted'], () => {
        searchForm.submit();
    });

    // When the search form is being submitted, refresh the search results
    bus.on('search-form::submit', (form) => {
        createTag.setLanguageId(form.languageId);
        searchResults.refresh(form);
    });

    searchForm.submit();
};