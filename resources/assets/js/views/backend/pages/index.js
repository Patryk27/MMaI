import DataTableComponent from '../../../components/DataTableComponent';
import app from '../../../Application';

/**
 * @returns {object}
 */
function getFilters() {
    const $filters = $('#pages-filters');

    return {
        type: {
            operator: 'in',
            value: $filters.find('[name="types[]"]').val(),
        },

        routeUrl: {
            operator: 'expression',
            value: $filters.find('[name="url"]').val(),
        },

        websiteId: {
            operator: 'in',
            value: $filters.find('[name="website_ids[]"]').val(),
        },

        status: {
            operator: 'in',
            value: $filters.find('[name="statuses[]"]').val(),
        },
    };
}

app.registerView('backend.pages.index', () => {
    const dataTable = new DataTableComponent({
        loaderSelector: '#pages-loader',
        tableSelector: '#pages-table',

        autofocus: true,
        source: '/pages/search',

        prepareRequest: (request) => {
            return Object.assign(request, {
                filters: getFilters(),
            });
        },
    });

    $('#pages-filters').on('change', () => {
        dataTable.refresh();
    });

    dataTable.refresh();
});
