import DataTableComponent from '../../../base/components/DataTableComponent';

/**
 * @returns {object}
 */
function getFilters() {
    const $filters = $('#requests-filters');

    // Parse the "created at" filter
    const createdAt = /^(.+) to (.+)$/.exec(
        $filters.find('[name="created_at"]').val(),
    );

    let createdAtFrom, createdAtTo;

    if (createdAt) {
        createdAtFrom = createdAt[1];
        createdAtTo = createdAt[2];
    }

    return {
        type: {
            operator: 'in',
            value: $filters.find('[name="types[]"]').val(),
        },

        url: {
            operator: 'expression',
            value: $filters.find('[name="url"]').val(),
        },

        created_at: {
            operator: 'expression',
            value: createdAt ? `:between(${createdAtFrom}, ${createdAtTo})` : null,
        },
    };
}

export default function () {
    const dataTable = new DataTableComponent({
        loaderSelector: '#requests-loader',
        tableSelector: '#requests-table',

        autofocus: true,
        source: '/analytics/requests/search',

        prepareRequest: (request) => {
            return Object.assign(request, {
                filters: getFilters(),
            });
        },
    });

    $('#requests-filters').on('change', () => {
        dataTable.refresh();
    });

    dataTable.refresh();
};
