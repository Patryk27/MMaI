import axios from 'axios';
import $ from 'jquery';

import 'bootstrap';
import 'datatables.net';
import 'datatables.net-bs4/js/dataTables.bootstrap4';

import DataTableComponent from './base/components/DataTableComponent';
import Dispatcher from './base/Dispatcher';

import './backend/pages';

window.$ = window.jQuery = $;

$(() => {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').val();

    Dispatcher.execute();

    $('[data-datatable]').each(function () {
        const config = $(this).data('datatable');
        config.tableSelector = $(this);

        const dataTable = new DataTableComponent(config);
        dataTable.refresh();
    });
});