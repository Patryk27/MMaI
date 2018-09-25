export default class AttachmentsTable {

    /**
     * @param {jQuery} $table
     */
    constructor($table) {
        this.$dom = {
            table: $table,
            rowTemplate: $table.find('.template'),
        };
    }

    /**
     * Binds a handler for given attachment-related action.
     *
     * @param {string} actionName
     * @param {function} actionHandler
     */
    onAction(actionName, actionHandler) {
        this.$dom.table.on('click', `[data-action=${actionName}]`, (evt) => {
            const $tr = $(evt.target).closest('tr');

            actionHandler(
                $tr.data('attachment'),
            );

            evt.stopPropagation();
        });
    }

    /**
     * Adds an attachment to the table.
     * For convenience, this method returns given attachment parameter.
     *
     * @param {object} attachment
     * @returns {object}
     */
    add(attachment) {
        const $row = this.$dom.rowTemplate.clone();

        $row.removeClass('template');
        $row.data('attachment', attachment);

        $row.appendTo(
            this.$dom.table.find('tbody'),
        );

        return this.update(attachment);
    }

    /**
     * Updates given attachment and refreshes its DOM.
     * For convenience, this method returns given attachment parameter.
     *
     * @param {object} attachment
     * @returns {object}
     */
    update(attachment) {
        const $row = this.$findRow(attachment.id);

        $row.data('attachment', attachment);

        // Update the DOM
        if (attachment.status !== 'being-uploaded') {
            $row.find('[data-column="id"]').text(attachment.id);
        }

        $row.find('[data-column="name"] .name').text(attachment.name);
        $row.find('[data-column="size"]').text(attachment.size);

        // Update the progress bar
        if (attachment.status === 'being-uploaded') {
            $row.find('.progress-bar').css({
                width: attachment.statusPayload.uploadedPercentage + '%',
            });
        } else {
            $row.find('.progress').hide();
        }

        return attachment;
    }

    /**
     * Removes attachment with specified id.
     *
     * @param {number} attachmentId
     */
    remove(attachmentId) {
        this.$findRow(attachmentId).remove();
    }

    /**
     * Returns all table's rows.
     *
     * @returns {jQuery}
     */
    getAll() {
        return this.$dom.table.find('tbody tr');
    }

    /**
     * @private
     *
     * Returns row for given attachment.
     * Throws an exception if no such row could have been found.
     *
     * @param {number} attachmentId
     * @returns {jQuery|null}
     */
    $findRow(attachmentId) {
        let $tr = null;

        this.$dom.table.find('tbody tr').each((_, el) => {
            const $el = $(el);

            if ($el.data('attachment').id === attachmentId) {
                $tr = $(el);
            }
        });

        if ($tr === null) {
            throw `Could not find attachment [id=${attachmentId}] in the table.`;
        }

        return $tr;
    }

}
