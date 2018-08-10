import $ from 'jquery';
import swal from 'sweetalert';

import LoaderComponent from './LoaderComponent';

/**
 * This component is a wrapper for the great jQuery DataTables - it provides an
 * additional functionality over the original one (e.g. adds support for loaders).
 */
export default class DataTableComponent {

    /**
     * @param {object} config
     */
    constructor(config) {
        this.$config = config;

        if (config.tableSelector === undefined) {
            throw 'Please specify the table\'s selector.';
        }

        // Initialize loader
        if (config.hasOwnProperty('loaderSelector')) {
            this.$loader = new LoaderComponent(config.loaderSelector);
        }

        // Prepare DataTable's columns
        const columns = DataTableComponent.$buildColumns(
            $(config.tableSelector),
        );

        // Initialize the DataTable
        this.$dt = $(config.tableSelector).DataTable({
            columns: columns,
            deferLoading: true,
            orderMulti: false,
            processing: true,
            searchDelay: 500,
            serverSide: true,

            ajax: (originalData, callback) => this.$dtAjax(originalData, callback),
            initComplete: () => this.$dtInitComplete(),
        });

        // Since the DataTable plugin destroys original container, we cannot
        // re-utilize $table here - we need to fetch the actual new container:
        this.$dom = {
            table: $(this.$dt.table().container()),
        };
    }

    /**
     * Force-refreshes the DataTable.
     */
    refresh() {
        this.$dt.ajax.reload();
    }

    /**
     * @private
     *
     * Returns data used to prepare Ajax request to the backend.
     *
     * @param {object} originalData
     * @returns {object}
     */
    $prepareRequest(originalData) {
        // Transform column's data (name, attributes, etc.) onto the names only;
        // Backend doesn't need to know anything more.
        const columns = originalData.columns.map((column) => column.name);

        // Prepare basic request
        const request = {
            columns,

            pagination: {
                page: originalData.start / originalData.length,
                perPage: originalData.length,
            },

            // We only support sorting by one column, so let's choose the first
            // one.
            orderBy: {
                column: columns[originalData.order[0].column],
                direction: originalData.order[0].dir,
            },

            search: originalData.search.value,
        };

        // If user's provided custom "prepare request" handler, call it now
        if (this.$config.hasOwnProperty('prepareRequest')) {
            return this.$config.prepareRequest(request);
        } else {
            return request;
        }
    }

    /**
     * @private
     *
     * Handler replacing the original DataTable's "ajax" method.
     *
     * @param {object} originalData
     * @param {function} callback
     */
    async $dtAjax(originalData, callback) {
        if (this.$loader) {
            this.$loader.show();
        }

        try {
            const response = await $.ajax({
                url: this.$config.source,
                data: this.$prepareRequest(originalData),
            });

            if (response.hasOwnProperty('data')) {
                callback(response);
            } else {
                DataTableComponent.$showErrorMessage();
            }
        } catch (ex) {
            console.error(ex);

            DataTableComponent.$showErrorMessage();

            callback({
                data: [],
            });
        }

        // Mark table as "ready";
        // We're doing it after a delay, because it seems that Firefox cannot
        // efficiently handle both re-building the new table and animating
        // it, which results in a stuttering animation.
        setTimeout(() => {
            if (this.$loader) {
                this.$loader.hide();
            }
        }, 200);
    }

    /**
     * @private
     *
     * Handler replacing the original DataTable's "initCompete" method.
     */
    $dtInitComplete() {
        if (this.$config.autofocus) {
            // The DataTable's finished loading, but we do not have access to
            // the "this.$dom.table" yet - we must wait a tick before doing so.
            setTimeout(() => {
                this.$dom.table
                    .find('.dataTables_filter input')
                    .focus();
            }, 0);
        }
    }

    /**
     * @private
     *
     * Parses the table's header and returns configuration for each column.
     *
     * @param {jQuery} $table
     * @returns {array<object>}
     */
    static $buildColumns($table) {
        const columns = [];

        $table.find('thead th').each(function () {
            let column = $(this).data('datatable-column');

            // Fill in the default configuration
            column = Object.assign({
                orderable: false,
            }, column);

            // Push column's configuration into the list
            columns.push({
                name: column.name,
                orderable: column.orderable,
            });
        });

        return columns;
    }

    /**
     * @private
     */
    static $showErrorMessage() {
        // noinspection JSIgnoredPromiseFromCall
        swal({
            title: 'Failed to load table',
            text: 'There was an error trying to load the table - please refresh page and try again.',
            icon: 'error',
        });
    }

}