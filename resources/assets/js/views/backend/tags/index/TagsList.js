import DataTableComponent from '../../../../components/DataTableComponent';

export default class TagsList {

    /**
     * @param {EventBus} bus
     * @param {jQuery} $loader
     * @param {jQuery} $table
     */
    constructor(bus, $loader, $table) {
        this.$dataTable = new DataTableComponent({
            loaderSelector: $loader,
            tableSelector: $table,

            autofocus: true,
            source: '/tags/search',

            prepareRequest: (request) => {
                return Object.assign(request, {
                    filters: this.$state.filters,
                });
            },
        });

        // Double-clicking the tag's name is a shorthand for opening the tag editor
        this.$dataTable.onColumn('name', 'dblclick', function () {
            bus.emit('tag::edit', {
                tag: $(this).data('tag'),
            });
        });

        this.$state = {
            filters: {},
        };
    }

    /**
     * @param {object} filters
     */
    refresh(filters) {
        this.$state.filters = {
            website_id: filters.websiteIds,
        };

        this.$dataTable.refresh();
    }

}
