import { app } from '../../../Application';
import { EventBus } from '../../../utils/EventBus';
import { TagsCreator } from './index/TagsCreator';
import { TagsDeleter } from './index/TagsDeleter';
import { TagsEditor } from './index/TagsEditor';
import { TagsFilters } from './index/TagsFilters';
import { TagsTable } from './index/TagsTable';

app.addViewInitializer('backend.tags.index', () => {
    const bus = new EventBus();

    const
        tagsCreator = new TagsCreator(bus, $('#create-tag-modal')),
        tagsDeleter = new TagsDeleter(bus),
        tagsEditor = new TagsEditor(bus, $('#edit-tag-modal')),
        tagsFilters = new TagsFilters(bus, $('#tags-filters')),
        tagsTable = new TagsTable(bus, $('#tags-loader'), $('#tags-table'));

    $('#create-tag-button').on('click', () => {
        tagsCreator.run();
    });

    // Bind handlers for the "edit tag" & "delete tag" buttons
    $('#tags-table').on('click', '[data-action]', function () {
        bus.emit('tag::' + $(this).data('action'), {
            tag: $(this).data('tag'),
        });
    });

    bus.on('tag::delete', ({ tag }) => {
        // noinspection JSIgnoredPromiseFromCall
        tagsDeleter.run(tag);
    });

    bus.on('tag::edit', ({ tag }) => {
        tagsEditor.run(tag);
    });

    bus.on(['tag::created', 'tag::updated', 'tag::deleted'], () => {
        tagsFilters.submit();
    });

    bus.on('filters::submitted', (filters) => {
        if (filters.websiteIds.value.length === 1) {
            tagsCreator.setWebsiteId(filters.websiteIds.value[0]);
        }

        tagsTable.refresh(filters);
    });

    tagsFilters.submit();
});
