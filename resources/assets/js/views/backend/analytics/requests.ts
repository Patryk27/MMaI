import { app } from '../../../Application';
import { InteractiveTable } from '../../../components/InteractiveTable';

function getFilters(): any {
    const filters = $('#requests-filters');

    const createdAt = /^(.+) to (.+)$/.exec(
        <string>filters.find('[name="created_at"]').val(),
    );

    let createdAtFrom, createdAtTo;

    if (createdAt) {
        createdAtFrom = createdAt[1];
        createdAtTo = createdAt[2];
    }

    return {
        requestUrl: {
            operator: 'expression',
            value: filters.find('[name="request_url"]').val(),
        },

        responseStatusCode: {
            operator: 'expression',
            value: filters.find('[name="response_status_code"]').val(),
        },

        createdAt: {
            operator: 'expression',
            value: createdAt ? `:between(${createdAtFrom}, ${createdAtTo})` : null,
        },
    };
}

app.addViewInitializer('backend.analytics.requests', () => {
    const dataTable = new InteractiveTable({
        autofocus: true,
        loaderSelector: '#requests-loader',
        source: '/api/analytics/requests',
        tableSelector: '#requests-table',

        onPrepareRequest(request) {
            return Object.assign(request, {
                filters: getFilters(),
            });
        },
    });

    $('#requests-filters').on('change', () => {
        dataTable.refresh();
    });

    dataTable.refresh();
});

