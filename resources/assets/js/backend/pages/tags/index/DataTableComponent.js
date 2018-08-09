import DataTable from '../../../../base/components/DataTable';

export default class DataTableComponent {

    /**
     * @param {string} loaderSelector
     * @param {string} tableSelector
     */
    constructor(loaderSelector, tableSelector) {
        this.$dataTable = new DataTable({
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