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
    onRowAction(actionName, actionHandler) {
        this.$dom.table.on('click', `[data-action=${actionName}]`, (evt) => {
            const $row = $(evt.target).closest('tr');

            const
                attachment = $row.data('attachment'),
                attachmentUrl = $row.data('attachment-url');

            if (attachmentUrl !== undefined) {
                attachment.url = attachmentUrl;
            }

            actionHandler({
                attachment,
                row: $row,
                target: $(evt.target),
            });

            evt.stopPropagation();
        });
    }

    /**
     * Adds an attachment to the table.
     * For convenience, this method returns given attachment.
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
     * For convenience, this method returns given attachment.
     *
     * @param {object} attachment
     * @returns {object}
     */
    update(attachment) {
        const $row = this.$findRow(attachment.id);

        $row.data('attachment', attachment);

        // If attachment's being uploaded, do not show the `id` column
        if (attachment.status !== 'being-uploaded') {
            $row.find('[data-column="id"]').text(attachment.id);
        }

        // Update the MIME type, name and size
        $row.find('[data-column="name"] .name').text(attachment.name);
        $row.find('[data-column="mime"]').text(attachment.mime);
        $row.find('[data-column="size"]').text(attachment.size);

        // Update the `download` button
        $row.find('[data-action="download"]').attr('href', attachment.url);

        // If attachment's being uploaded, show the progress bar
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
     * Returns all table's rows as a jQuery selector.
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
        let $row = null;

        this.$dom.table.find('tbody tr').each((_, el) => {
            const $rowCandidate = $(el);

            if ($rowCandidate.data('attachment').id === attachmentId) {
                $row = $rowCandidate;
            }
        });

        if ($row === null) {
            throw `Could not find attachment [id=${attachmentId}] in the table.`;
        }

        return $row;
    }

}
