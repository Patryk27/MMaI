import { TagsFacade } from '@/api/tags/TagsFacade';
import { app } from '@/Application';
import { CreateTagModal } from '@/components/tags/CreateTagModal';
import { EditTagModal } from '@/components/tags/EditTagModal';
import { TagsFilters } from '@/components/tags/TagsFilters';
import { TagsTable } from '@/components/tags/TagsTable';
import { ContextMenu } from '@/ui/components';
import { EventBus } from '@/utils/EventBus';

app.onViewReady('backend.tags.index', () => {
    const bus = new EventBus();

    const createTagModal = new CreateTagModal($('#create-tag-modal'));
    const editTagModal = new EditTagModal($('#edit-tag-modal'));

    const tagsFilters = new TagsFilters({
        dom: {
            container: $('#tags-filters'),
        },

        events: {
            onChange() {
                tagsTable.refresh(tagsFilters.get);
            },
        },
    });

    const tagsTable = new TagsTable({
        dom: {
            loader: $('#tags-loader'),
            table: $('#tags-table'),
        },

        events: {
            onEdit(tag) {
                editTagModal
                    .show(tag)
                    .then(() => bus.emit('tag::updated'))
                    .catch(window.onerror);
            },

            onDelete(el, tag) {
                const menu = new ContextMenu({
                    actions: {
                        delete: {
                            title: 'Delete',
                            class: 'btn-danger',

                            async handle() {
                                await TagsFacade.delete(tag.id);
                                bus.emit('tag::deleted');
                            },
                        },
                    },
                });

                menu.show(el);
            },
        },
    });

    $('#create-tag-button').on('click', () => {
        createTagModal
            .show()
            .then(() => bus.emit('tag::created'))
            .catch(window.onerror);
    });

    bus.on(['tag::created', 'tag::updated', 'tag::deleted'], () => {
        tagsTable.refresh(tagsFilters.get);
    });

    tagsTable.refresh(tagsFilters.get);
});
