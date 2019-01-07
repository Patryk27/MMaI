import { app } from '../../../Application';
import { EventBus } from '../../../utils/EventBus';
import { Tippy } from '../../../utils/Tippy';
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

    $('#tags-table').on('click', '[data-action="delete"]', function () {
        const tooltip = $('<div>');

        $('<a>')
            .addClass('btn btn-sm btn-danger')
            .text('Delete')
            .on('click', () => {
                // noinspection JSIgnoredPromiseFromCall
                tagsDeleter.delete($(this).data('tag'));
            })
            .appendTo(tooltip);

        Tippy.once($(this), {
            animation: 'shift-away',
            arrow: true,
            content: tooltip.get(0),
            interactive: true,
            placement: 'bottom',
        });
    });

    bus.on(['tag::created', 'tag::updated', 'tag::deleted'], () => {
        tagsTable.refresh();
    });

    tagsTable.refresh();
});
