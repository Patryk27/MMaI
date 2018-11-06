import axios from 'axios';

// Load jQuery
import $ from 'jquery';
window.$ = window.jQuery = $;

// Load Moment.js
import moment from 'moment';
window.moment = moment;

// Load other vendor's dependencies
import 'bootstrap';
require('bootstrap-add-clear');
import 'datatables.net';
import 'datatables.net-bs4/js/dataTables.bootstrap4';
import 'select2';

// Load other our dependencies
import DataTableComponent from './base/components/DataTableComponent';
import Dispatcher from './base/Dispatcher';

import './backend/views';

// It seems that `flatpickr` has some issues while loaded using `import`
// noinspection JSUnresolvedFunction
window.flatpickr = require('flatpickr');

$(() => {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').val();
    flatpickr.l10ns.default.firstDayOfWeek = 1;

    // Initialize all the DataTables
    $('[data-datatable]').each(function () {
        const config = $(this).data('datatable');
        config.tableSelector = $(this);

        const dataTable = new DataTableComponent(config);
        dataTable.refresh();
    });

    // Initialize all the flatpickrs
    $('[type="datetime"]').each(function () {
        const config = $(this).data('datetime') || {};
        config.enableTime = true;

        $(this).flatpickr(config);
    });

    // Initialize all the Select2s
    $('.select2').each(function () {
        $(this).select2({
            width: '100%',
        });
    });

    // Initialize all the clearable inputs
    $('.input-clearable').each(function () {
        // noinspection JSUnusedGlobalSymbols
        $(this).addClear({
            symbolClass: 'fa fa-times-circle',

            onClear: () => {
                $(this).trigger('change');
            },
        });
    });

    Dispatcher.execute();
});
