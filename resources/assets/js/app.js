import $ from 'jquery';
window.$ = window.jQuery = $;

import moment from 'moment';
window.moment = moment;

import 'bootstrap';
import 'datatables.net';
import 'datatables.net-bs4/js/dataTables.bootstrap4';
import 'select2';
import './views';
import app from './Application';

require('bootstrap-add-clear');

window.flatpickr = require('flatpickr');

$(() => {
    app.run(
        $('body').data('view'),
    );
});
