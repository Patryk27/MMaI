import 'bootstrap';
import 'datatables.net';
import 'datatables.net-bs4/js/dataTables.bootstrap4';
import jQuery from 'jquery';
import moment from 'moment';
import 'select2';
import swal from 'sweetalert';
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

window.onerror = (error) => {
    const content = $('<div>');

    $('<p>')
        .text('Sorry, the application has encountered an unexpected error - please refresh the page and try again.')
        .appendTo(content);

    $('<p>')
        .text('Details:')
        .appendTo(content);

    $('<pre>')
        .text(error.toString())
        .appendTo(content);

    // noinspection JSIgnoredPromiseFromCall
    swal({
        content: { element: content[0] },
        title: 'Application crashed',
        icon: 'error',
        className: 'without-buttons',
        buttons: {},
        closeOnClickOutside: false,
        closeOnEsc: false,
    });
};

$(() => {
    app.run(
        $('body').data('view'),
    );
});
