import DataTableComponent from '../../../../base/components/DataTableComponent';

export default class SearchResultsComponent {

    /**
     * @param {Bus} bus
     * @param {string} loaderSelector
     * @param {string} tableSelector
     */
    constructor(bus, loaderSelector, tableSelector) {
        this.$bus = bus;

        this.$dataTable = new DataTableComponent({
            loaderSelector,
            tableSelector,

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