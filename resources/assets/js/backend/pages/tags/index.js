import Bus from '../../../base/Bus';
import TagCreator from './index/TagCreator';
import TagDeleter from './index/TagDeleter';
import TagEditor from './index/TagEditor';
import TagsFilters from './index/TagsFilters';
import TagsList from './index/TagsList';

export default function () {
    const bus = new Bus();

    const
        tagCreator = new TagCreator(bus, $('#create-tag-modal')),
        tagEditor = new TagEditor(bus, $('#edit-tag-modal')),
        tagDeleter = new TagDeleter(bus);

    const
        tagsFilters = new TagsFilters(bus, $('#tags-filters')),
        tagsList = new TagsList(bus, $('#tags-loader'), $('#tags-table'));

    $('#create-tag-button').on('click', () => {
        tagCreator.create();
    });

    // Bind handlers for the "edit tag" & "delete tag" buttons
    $('#tags-table').on('click', '.btn-tag-action', function () {
        bus.emit('tag::' + $(this).data('action'), {
            tag: $(this).data('tag'),
        });
    });

    bus.on('tag::delete', ({ tag }) => {
        // noinspection JSIgnoredPromiseFromCall
        tagDeleter.delete(tag);
    });

    bus.on('tag::edit', ({ tag }) => {
        tagEditor.edit(tag);
    });

    bus.onAny(['tag::created', 'tag::updated', 'tag::deleted'], () => {
        tagsFilters.submit();
    });

    bus.on('filters::submitted', (filters) => {
        if (filters.languageIds.length === 1) {
            tagCreator.setLanguageId(filters.languageIds[0]);
        }

        tagsList.refresh(filters);
    });

    tagsFilters.submit();
};
