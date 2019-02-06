import { app } from '@/Application';
import { ContextMenu } from '@/ui/components';
import { EventBus } from '@/utils/EventBus';
import { TagsCreator } from './index/TagsCreator';
import { TagsDeleter } from './index/TagsDeleter';
import { TagsEditor } from './index/TagsEditor';
import { TagsTable } from './index/TagsTable';

app.addViewInitializer('backend.tags.index', () => {
    const bus = new EventBus();

    const
        tagsCreator = new TagsCreator(bus, $('#create-tag-modal')),
        tagsEditor = new TagsEditor(bus, $('#edit-tag-modal')),
        tagsDeleter = new TagsDeleter(bus),
        tagsTable = new TagsTable(bus, $('#tags-filters'), $('#tags-loader'), $('#tags-table'));

    $('#create-tag-button').on('click', () => {
        tagsCreator.create();
    });

    $('#tags-table').on('click', '[data-action="edit"]', function () {
        tagsEditor.edit($(this).data('tag'));
    });

    $('#tags-table').on('click', '[data-action="delete"]', async function () {
        const menu = new ContextMenu({
            actions: {
                delete: {
                    title: 'Delete',
                    cssClass: 'btn-danger',

                    handle: () => {
                        // noinspection JSIgnoredPromiseFromCall
                        tagsDeleter.delete($(this).data('tag'));
                    },
                },
            },
        });

        menu.run($(this));
    });

    bus.on(['tag::created', 'tag::updated', 'tag::deleted'], () => {
        tagsTable.refresh();
    });

    tagsTable.refresh();
});
