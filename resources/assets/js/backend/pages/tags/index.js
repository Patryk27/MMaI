import DataTables from '../../../base/components/DataTable';

export default function () {
    const $form = $('#tags-form');

    $form.on('change', 'select', () => {
        dataTable.refresh();
    });

    const dataTable = new DataTables({
        autofocus: true,
        loader: '#tags-loader',
        source: '/backend/tags/search',
        table: '#tags-table',

        prepareRequest(request) {
            request.filters = {
                language_id: $form.find('[name="language_id"]').val(),
            };

            return request;
        },
    });
};