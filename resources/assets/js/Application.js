import $ from 'jquery';
import axios from 'axios';
import DataTableComponent from './components/DataTableComponent';

export default new class Application {

    constructor() {
        this.$views = {};
    }

    /**
     * @param {string} name
     * @param {function} handler
     */
    registerView(name, handler) {
        this.$views[name] = handler;
    }

    /**
     * @param {string} viewName
     */
    run(viewName) {
        axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').val();
        window.flatpickr.l10ns.default.firstDayOfWeek = 1;

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

        // Start the view
        if (this.$views.hasOwnProperty(viewName)) {
            this.$views[viewName]();
        }
    }

};
