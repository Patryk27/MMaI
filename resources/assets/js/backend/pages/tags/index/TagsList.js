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
            source: '/backend/tags/search',

            prepareRequest: (request) => {
                request.filters = this.$state.filters;

                return request;
            },
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