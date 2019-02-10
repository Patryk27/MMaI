import { Attachment } from '@/api/attachments/Attachment';

export class AttachmentsTable {

    private readonly rowTemplate: JQuery;

    constructor(private readonly table: JQuery) {
        this.rowTemplate = table.find('.template');
    }

    public onAttachment(event: string, handler: (payload: any) => void): void {
        this.table.on('click', `[data-action=${event}]`, (evt) => {
            const row = $(evt.target).closest('tr');

            handler({
                attachment: row.data('attachment'),
            });

            evt.stopPropagation();
        });
    }

    public add(attachment: Attachment): Attachment {
        const row = this.rowTemplate.clone();

        row
            .data('attachment', attachment)
            .removeClass('template')
            .appendTo(this.table.find('tbody'));

        return this.update(attachment);
    }

    public update(attachment: Attachment): Attachment {
        const row = this.findRow(attachment.id);

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
        this.findRow(attachmentId).remove();
    }

    public getAll(): JQuery {
        return this.table.find('tbody tr');
    }

    private findRow(attachmentId: number): JQuery {
        let row = null;

        this.table.find('tbody tr').each((_, el) => {
            const rowCandidate = $(el);

            if (rowCandidate.data('attachment').id === attachmentId) {
                row = rowCandidate;
            }
        });

        if (row === null) {
            throw `Could not find attachment [id=${attachmentId}] in the table.`;
        }

        return row;
    }

}
