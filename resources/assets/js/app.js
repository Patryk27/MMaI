import $ from 'jquery';

import 'bootstrap';
import 'datatables.net';
import 'datatables.net-bs4/js/dataTables.bootstrap4';

import DataTable from './base/components/DataTable';
import Dispatcher from './base/Dispatcher';

import './backend/pages';

window.$ = window.jQuery = $;

$(() => {
    Dispatcher.execute();

    $('[data-datatable]').each(function () {
        const config = $(this).data('datatable');
        config.table = $(this);

        const dataTable = new DataTable(config);
        dataTable.refresh();
    });
});