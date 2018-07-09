import $ from 'jquery';

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
            orderMulti: false,
            processing: true,
            searchDelay: 500,
            serverSide: true,
        });

        // Since the DataTable plugin destroys original container, we cannot
        // re-utilize $table here - we need to fetch the actual new container:
        this.$dom = {
            table: $(dtInstance.table().container()),
        };
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
        // Transform column's data (name, attributes, etc.) onto the names only;
        // Backend doesn't have to know anything more whatsoever.
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
            if (!response.hasOwnProperty('data')) {
                alert('$.ajax() failed (1).'); // @todo
            }

            callback(response);
        }).fail(() => {
            alert('$.ajax() failed (2).'); // @todo

            callback({
                data: [],
            });
        });
    }

    /**
     * @internal
     *
     * Handler replacing the original DataTable's "initCompete" method.
     */
    $dtInitComplete() {
        if (this.$config.autofocus) {
            this.$dom.table
                .find('.dataTables_filter input')
                .focus();
        }
    }

    /**
     * @internal
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
        };
    }

    /**
     * @internal
     *
     * Parses the table's header and returns configuration for each column.
     *
     * @param {jQuery} $table
     * @returns {Array<Object>}
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

}