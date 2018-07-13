import DataTable from '../../../base/components/DataTable';

export default function () {
    new DataTable({
        autofocus: true,
        loader: '#tags-loader',
        source: '/backend/tags/search',
        table: '#tags-table',

        prepareRequest(request) {
            request.filters = {
                language_id: 1,
            };

            return request;
        },
    });
};