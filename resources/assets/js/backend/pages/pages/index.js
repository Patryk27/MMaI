import DataTableComponent from '../../../base/components/DataTableComponent';

/**
 * @returns {object}
 */
function getFilters() {
    const $filters = $('#pages-filters');

    return {
        page_type: $filters.find('[name="types[]"]').val(),
        route_url: $filters.find('[name="url"]').val(),
        language_id: $filters.find('[name="language_ids[]"]').val(),
        status: $filters.find('[name="statuses[]"]').val(),
    };
}

export default function () {
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
};
