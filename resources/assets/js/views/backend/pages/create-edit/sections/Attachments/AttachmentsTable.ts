import { Attachment } from '../../../../../../api/attachments/Attachment';

export class AttachmentsTable {

    private readonly dom: {
        table: JQuery,
        rowTemplate: JQuery,
    };

    constructor(table: JQuery) {
        this.dom = {
            table,
            rowTemplate: table.find('.template'),
        };
    }

    public onRowAction(actionName: string, actionHandler: (payload: any) => void): void {
        this.dom.table.on('click', `[data-action=${actionName}]`, (evt) => {
            const row = $(evt.target).closest('tr');

            const
                attachment = row.data('attachment'),
                attachmentUrl = row.data('attachment-url');

            if (attachmentUrl !== undefined) {
                attachment.url = attachmentUrl;
            }

            actionHandler({
                attachment,
                row,
                target: $(evt.target),
            });

            evt.stopPropagation();
        });
    }

    public add(attachment: Attachment): Attachment {
        const $row = this.dom.rowTemplate.clone();

        $row.removeClass('template');
        $row.data('attachment', attachment);

        $row.appendTo(
            this.dom.table.find('tbody'),
        );

        return this.update(attachment);
    }

    public update(attachment: Attachment): Attachment {
        const row = this.findRowForAttachment(attachment.id);

        row.data('attachment', attachment);

        // If attachment's being uploaded, do not show the `id` column
        if (attachment.status !== 'being-uploaded') {
            row.find('[data-column="id"]').text(attachment.id);
        }

        // Update the MIME type, name and size
        row.find('[data-column="name"] .name').text(attachment.name);
        row.find('[data-column="mime"]').text(attachment.mime);
        row.find('[data-column="size"]').text(attachment.size);

        // Update the `download` button
        row.find('[data-action="download"]').attr('href', attachment.url);

        // If attachment's being uploaded, show the progress bar
        if (attachment.status === 'being-uploaded') {
            row.find('.progress-bar').css({
                width: attachment.statusPayload.uploadedPercentage + '%',
            });
        } else {
            row.find('.progress').hide();
        }

        return attachment;
    }

    public remove(attachmentId: number): void {
        this.findRowForAttachment(attachmentId).remove();
    }

    public getAllSelector(): JQuery {
        return this.dom.table.find('tbody tr');
    }

    private findRowForAttachment(id: number): JQuery {
        let row = null;

        this.dom.table.find('tbody tr').each((_, el) => {
            const rowCandidate = $(el);

            if (rowCandidate.data('attachment').id === id) {
                row = rowCandidate;
            }
        });

        if (row === null) {
            throw `Could not find attachment [id=${id}] in the table.`;
        }

        return row;
    }

}
