import Bus from '../../../base/Bus';
import TagCreator from './index/TagCreator';
import TagDeleter from './index/TagDeleter';
import TagEditor from './index/TagEditor';
import SearchForm from './index/SearchForm';
import TagsList from './index/TagsList';

export default function () {
    const bus = new Bus();

    // noinspection JSUnusedLocalSymbols
    const
        tagCreator = new TagCreator(bus, $('#create-tag-modal')),
        tagEditor = new TagEditor(bus, $('#edit-tag-modal')),
        tagDeleter = new TagDeleter(bus);

    const
        searchForm = new SearchForm(bus, $('#tags-search-form')),
        tagsList = new TagsList(bus, $('#tags-loader'), $('#tags-table'));

    // When user clicks on the "create tag" button, open the "create tag" modal
    $('#create-tag-button').on('click', () => {
        tagCreator.run();
    });

    // When user clicks on the "edit tag" / "delete tag" buttons, execute appropriate action
    $('#tags-table').on('click', '.btn-tag-action', (evt) => {
        const
            $target = $(evt.currentTarget),
            tag = $target.data('tag');

        switch ($target.data('action')) {
            case 'edit':
                tagEditor.run(tag);
                break;

            case 'delete':
                // noinspection JSIgnoredPromiseFromCall
                tagDeleter.run(tag);
                break;
        }
    });

    // When a new tag is created or updated, refresh the search results
    bus.onMany(['tag::created', 'tag::updated', 'tag::deleted'], () => {
        searchForm.submit();
    });

    // When the search form is being submitted, refresh the search results
    bus.on('search-form::submit', (form) => {
        tagCreator.setLanguageId(form.languageId);
        tagsList.refresh(form);
    });

    searchForm.submit();
};