import DataTableComponent from '../../../../base/components/DataTableComponent';

export default class TagsList {

    /**
     * @param {Bus} bus
     * @param {jQuery} $loader
     * @param {jQuery} $table
     */
    constructor(bus, $loader, $table) {
        this.$bus = bus;

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
     * @param {object} form
     */
    refresh(form) {
        this.$state.filters = {
            language_id: form.languageId,
        };

        this.$dataTable.refresh();
    }

}
