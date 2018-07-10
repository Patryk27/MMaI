import axios from 'axios';
import swal from 'sweetalert';

class Page {

    constructor() {
        this.$dom = {
            form: $('#tags-form'),
            loader: $('#tags-loader'),
        };

        this.$dom.form.on('change', '*', () => {
            // noinspection JSIgnoredPromiseFromCall
            this.refresh();
        });

        this.$table = new Table();

        // noinspection JSIgnoredPromiseFromCall
        this.refresh();
    }

    /**
     * @returns {Promise<void>}
     */
    async refresh() {
        this.$table.hide();
        this.$dom.loader.show();

        try {
            const response = await axios.post('/backend/tags/search', this.getSearchPayload());

            this.$table.refresh(response.data.items);
        } catch (ex) {
            console.error(ex);

            // noinspection JSIgnoredPromiseFromCall
            swal({
                title: 'Failed to load tags',
                text: 'Failed to load tags - please refresh page and try again.',
                icon: 'error',
            });
        }

        this.$table.show();
        this.$dom.loader.hide();
    }

    /**
     * @returns {object}
     */
    getSearchPayload() {
        return {
            filters: {
                language_id: this.$dom.form.find('[name="language_id"]').val(),
            },
        };
    }

}

class Table {

    constructor() {
        const $container = $('#tags-table');

        this.$dom = {
            container: $container,
            tbody: $container.find('tbody'),
        };
    }

    /**
     * Shows the table.
     */
    show() {
        this.$dom.container.show();
    }

    /**
     * Hides the table.
     */
    hide() {
        this.$dom.container.hide();
    }

    /**
     * Re-feeds table with given data.
     *
     * @param {array<object>} tags
     */
    refresh(tags) {
        this.$dom.tbody.empty();

        tags.forEach((tag) => {
            const $tr = $('<tr>');

            $('<td>')
                .text(tag.id)
                .appendTo($tr);

            $('<td>')
                .text(tag.name)
                .appendTo($tr);

            $('<td>')
                .text(0) // @todo
                .appendTo($tr);

            $('<td>') // @todo
                .appendTo($tr);

            $tr.appendTo(
                this.$dom.container,
            );
        });
    }

}

export default function () {
    new Page();
};