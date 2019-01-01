import 'bootstrap';
import 'datatables.net';
import 'datatables.net-bs4/js/dataTables.bootstrap4';
import jQuery from 'jquery';
import moment from 'moment';
import 'select2';
import '../css/app.scss';

import { app } from './Application';
import './views';

// @ts-ignore
window.$ = window.jQuery = jQuery;

// @ts-ignore
window.moment = moment;

// @ts-ignore
require('bootstrap-add-clear');

// @ts-ignore
window.flatpickr = require('flatpickr');

$(() => {
    app.run(
        $('body').data('view'),
    );
});
