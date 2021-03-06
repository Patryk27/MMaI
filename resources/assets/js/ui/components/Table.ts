import { ApiClient } from '@/modules/core/ApiClient';
import $ from 'jquery';
import swal from 'sweetalert';
import { Loader } from './Loader';

interface Configuration {
    autofocus?: boolean;
    loaderSelector?: string | JQuery;
    source: string;
    tableSelector: string | JQuery;

    onPrepareRequest?: (request: any) => any;
}

export class Table {

    private readonly dataTable: any;
    private readonly loader?: Loader;
    private readonly table: JQuery;

    public constructor(private readonly config: Configuration) {
        if (config.loaderSelector) {
            this.loader = new Loader(config.loaderSelector);
        }

        const columns = Table.buildColumns(
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
        this.table = $(this.dataTable.table().gridContainer());
    }

    public on(eventName: string, eventHandler: () => void): void {
        this.table.on(eventName, eventHandler);
    }

    public onRowAction(eventName: string, actionName: string, actionHandler: () => void): void {
        this.table.on(eventName, `[data-action="${actionName}"]`, actionHandler);
    }

    public refresh(): void {
        this.dataTable.ajax.reload();
    }

    private buildRequest(dtRequest: any): any {
        const columns = dtRequest.columns.map((column: any) => column.name);

        const request = {
            columns,
            query: dtRequest.search.value,

            pagination: {
                page: dtRequest.start / dtRequest.length,
                perPage: dtRequest.length,
            },

            orderBy: {
                [columns[dtRequest.order[0].column]]: dtRequest.order[0].dir,
            },
        };

        return this.config.onPrepareRequest ? this.config.onPrepareRequest(request) : request;
    }

    private async dtAjax(dtRequest: any, callback: (response: any) => void): Promise<void> {
        if (this.loader) {
            this.loader.show();
        }

        try {
            const response: any = await ApiClient.request({
                method: 'get',
                url: this.config.source,
                params: {
                    query: JSON.stringify(this.buildRequest(dtRequest)),
                    render: 1,
                },
            });

            callback({
                recordsTotal: response.allCount,
                recordsFiltered: response.matchingCount,
                data: response.items,
            });
        } catch (error) {
            // noinspection JSIgnoredPromiseFromCall
            swal({
                title: 'Failed to refresh table',
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
            // the "this.table" yet - we must wait a tick before doing so.
            setTimeout(() => {
                this.table.find('.dataTables_filter input').focus();
            });
        }
    }

    private static buildColumns(table: JQuery): Array<{ name: string, orderable: boolean }> {
        const columns: Array<{ name: string, orderable: boolean }> = [];

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
