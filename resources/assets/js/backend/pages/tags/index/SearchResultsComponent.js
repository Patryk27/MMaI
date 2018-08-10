import DataTableComponent from '../../../../base/components/DataTableComponent';

export default class SearchResultsComponent {

    /**
     * @param {string} loaderSelector
     * @param {string} tableSelector
     */
    constructor(loaderSelector, tableSelector) {
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
     * @param {object} filters
     */
    refresh(filters) {
        this.$state.filters = filters;
        this.$dataTable.refresh();
    }

}