import $ from 'jquery';
import swal from 'sweetalert';

export default class DataTable {

    /**
     * @param {jQuery} $table
     */
    constructor($table) {
        // Prepare configuration
        this.$config = DataTable.$buildConfiguration($table);

        // Prepare columns' configuration
        const columns = DataTable.$buildColumns($table);

        // Initialize the DataTable
        const dtInstance = $table.DataTable({
            ajax: (originalData, callback) => this.$dtAjax(originalData, callback),
            initComplete: () => this.$dtInitComplete(),

            columns: columns,
            deferLoading: true,
            orderMulti: false,
            processing: true,
            searchDelay: 500,
            serverSide: true,
        });

        // Since the DataTable plugin destroys original container, we cannot
        // re-utilize $table here - we need to fetch the actual new container:
        this.$dom = {
            loader: $(this.$config.loader),
            table: $(dtInstance.table().container()),
        };

        dtInstance.ajax.reload();
    }

    /**
     * @private
     *
     * Handler replacing the original DataTable's "ajax" method.
     *
     * @param {object} originalData
     * @param {function} callback
     */
    $dtAjax(originalData, callback) {
        this.$dom.loader.addClass('visible');
        this.$dom.table.addClass('refreshing');

        // Re-position the loader
        this.$dom.loader.css({
            position: 'absolute',

            top: this.$dom.table.offset().top,
            left: this.$dom.table.offset().left,

            width: this.$dom.table.width(),
            height: this.$dom.table.height(),
        });

        // Transform column's data (name, attributes, etc.) onto the names only;
        // Backend doesn't need to know anything more.
        const columns = originalData.columns.map((column) => column.name);

        $.ajax({
            url: this.$config.source,
            data: {
                columns: columns,

                pagination: {
                    page: originalData.start / originalData.length,
                    perPage: originalData.length,
                },

                orderBy: {
                    column: columns[originalData.order[0].column],
                    direction: originalData.order[0].dir,
                },

                search: originalData.search.value,
            },
        }).done((response) => {
            if (response.hasOwnProperty('data')) {
                callback(response);
            } else {
                DataTable.$showErrorMessage();
            }
        }).fail(() => {
            DataTable.$showErrorMessage();

            callback({
                data: [],
            });
        }).always(() => {
            this.$dom.loader.removeClass('visible');
            this.$dom.table.removeClass('refreshing');
        });
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
     * Parses the table's configuration.
     *
     * @param {jQuery} $table
     * @returns {Object}
     */
    static $buildConfiguration($table) {
        let config = $table.data('datatable');

        // Fill in the default configuration
        config = Object.assign({
            focus: false,
        }, config);

        return {
            source: config.source,
            autofocus: config.autofocus,
            loader: config.loader || '',
        };
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