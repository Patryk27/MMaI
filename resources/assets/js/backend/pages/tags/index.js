import swal from 'sweetalert';
import DataTables from '../../../base/components/DataTable';

async function handleCreateTag() {
    const tag = await swal({
        title: 'Creating new Polish tag',
        text: 'Enter tag\'s name:',
        content: 'input',

        buttons: {
            cancel: 'Cancel',
            create: 'Create',
        },
    });

    alert(tag);
}

export default function () {
    const
        $createTagButton = $('#create-tag-button'),
        $searchForm = $('#tags-search-form');

    $createTagButton.on('click', () => {
        // noinspection JSIgnoredPromiseFromCall
        handleCreateTag();
    });

    $searchForm.on('change', 'select', () => {
        dataTable.refresh();
    });

    const dataTable = new DataTables({
        autofocus: true,
        loader: '#tags-search-loader',
        source: '/backend/tags/search',
        table: '#tags-table',

        prepareRequest(request) {
            request.filters = {
                language_id: $searchForm.find('[name="language_id"]').val(),
            };

            return request;
        },
    });
};