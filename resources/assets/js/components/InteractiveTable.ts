import $ from 'jquery';
import swal from 'sweetalert';
import { ApiClient } from '../api/ApiClient';
import { Loader } from './Loader';

interface DataTableComponentConfiguration {
    tableSelector: string | JQuery;
    loaderSelector?: string | JQuery;
    onPrepareRequest?: (request: any) => any;
    source: string;
    autofocus?: boolean;
}

export class InteractiveTable {

    private readonly config: DataTableComponentConfiguration;
    private readonly dataTable: any;
    private readonly loader?: Loader;

    private dom: {
        table: JQuery,
    };

    constructor(config: DataTableComponentConfiguration) {
        this.config = config;

        if (config.loaderSelector) {
            this.loader = new Loader(config.loaderSelector);
        }

        const columns = InteractiveTable.buildColumns(
            $(<any>config.tableSelector),
        );

        // @ts-ignore
        // noinspection JSUnusedGlobalSymbols
        this.dataTable = $(config.tableSelector).DataTable({
            autoWidth: false,
            columns: columns,
            deferLoading: true,
            orderMulti: false,
            processing: true,
            searchDelay: 500,
            serverSide: true,

            ajax: (originalData: any, callback: any) => this.dtAjax(originalData, callback),
            initComplete: () => this.dtInitComplete(),
        });

        // Since the DataTable plugin destroys original container, we cannot
        // re-utilize $table here - we need to fetch the actual new container:
        this.dom = {
            table: $(this.dataTable.table().container()),
        };
    }

    /**
     * Binds an event handler to the table.
     *
     * Example:
     *   on('click', () => { ... })
     */
    public on(eventName: string, eventHandler: () => void): void {
        this.dom.table.on(eventName, eventHandler);
    }

    /**
     * Binds an event handler for given column.
     *
     * # Example
     *
     * table.onColumn('some-column', 'click', () => { ... })
     */
    public onColumn(columnName: string, eventName: string, eventHandler: () => void): void {
        this.dom.table.on(eventName, `[data-column="${columnName}"]`, eventHandler);
    }

    public refresh(): void {
        this.dataTable.ajax.reload();
    }

    private buildRequest(dtRequest: any): any {
        const columns = dtRequest.columns.map((column: any) => column.name);

        const request = {
            columns,

            textQuery: dtRequest.search.value,

            pagination: {
                page: dtRequest.start / dtRequest.length,
                perPage: dtRequest.length,
            },

            // @todo make sure sorting works correctly
            orderBy: {
                column: columns[dtRequest.order[0].column],
                direction: dtRequest.order[0].dir,
            },
        };

        return this.config.onPrepareRequest ? this.config.onPrepareRequest(request) : request;
    }

    private async dtAjax(dtRequest: any, callback: (response: any) => void): Promise<void> {
        if (this.loader) {
            this.loader.show();
        }

        try {
            callback(await ApiClient.request({
                method: 'post',
                url: this.config.source,
                data: this.buildRequest(dtRequest),
            }));
        } catch (error) {
            // noinspection JSIgnoredPromiseFromCall
            swal({
                title: 'Failed to load table',
                text: error.toString(),
                icon: 'error',
            });

            callback({
                data: [],
            });
        }

        // Hide the table's loader.
        // We're doing it after a small delay, since it seems that at least Firefox cannot efficiently handle both
        // re-building the table and animating it at the same time.
        setTimeout(() => {
            if (this.loader) {
                this.loader.hide();
            }
        }, 200);
    }

    private dtInitComplete(): void {
        if (this.config.autofocus) {
            // The DataTable's finished loading, but we do not have access to
            // the "this.dom.table" yet - we must wait a tick before doing so.
            setTimeout(() => {
                this.dom.table.find('.dataTables_filter input').focus();
            });
        }
    }

    private static buildColumns(table: JQuery): Array<{ name: String, orderable: boolean }> {
        const columns: Array<{ name: String, orderable: boolean }> = [];

        table.find('thead th').each(function () {
            let column = $(this).data('datatable-column');

            column = Object.assign({
                orderable: false,
            }, column);

            columns.push(column);
        });

        return columns;
    }

}