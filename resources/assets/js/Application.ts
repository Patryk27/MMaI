import axios from 'axios';
import flatpickr from 'flatpickr';
import $ from 'jquery';
import { InteractiveTable } from './ui/components';

interface ViewInitializers {
    [name: string]: () => void;
}

class Application {

    private viewInitializers: ViewInitializers = {};

    public addViewInitializer(viewName: string, viewInitializer: () => void): void {
        this.viewInitializers[viewName] = viewInitializer;
    }

    public run(viewName: string): void {
        axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').val();
        flatpickr.l10ns.default.firstDayOfWeek = 1;

        // Initialize all the DataTables
        $('[data-datatable]').each(function () {
            const config = $(this).data('datatable');
            config.tableSelector = $(this);

            (new InteractiveTable(config)).refresh();
        });

        // Initialize all the flatpickrs
        $('[type="datetime"]').each(function () {
            const config = $(this).data('datetime') || {};
            config.enableTime = true;

            // @ts-ignore
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
            // @ts-ignore
            // noinspection JSUnusedGlobalSymbols
            $(this).addClear({
                symbolClass: 'fa fa-times-circle',

                onClear: () => {
                    $(this).trigger('change');
                },
            });
        });

        // Start the view
        if (this.viewInitializers.hasOwnProperty(viewName)) {
            this.viewInitializers[viewName]();
        }
    }

}

export const app = new Application();
