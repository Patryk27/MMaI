import { Attachment } from '@/modules/attachments/Attachment';

export class AttachmentsTable {

    private readonly rowTemplate: JQuery;

    constructor(private readonly table: JQuery) {
        this.rowTemplate = table.find('.template');
    }

    public onAttachment(actionName: string, actionHandler: (attachment: Attachment) => void): void {
        this.table.on('click', `[data-action=${actionName}]`, (evt) => {
            const row = $(evt.target).closest('tr');

            actionHandler(
                row.data('attachment'),
            );

            evt.stopPropagation();
        });
    }

    public add(attachment: Attachment): void {
        this.rowTemplate
            .clone()
            .removeClass('template')
            .data('attachment', attachment)
            .appendTo(this.table.find('tbody'));

        this.update(attachment);
    }

    public update(attachment: Attachment): void {
        const row = this.findRow(attachment.id);

        row.data('attachment', attachment);
        row.find('[data-column="id"]').text(attachment.id);
        row.find('[data-column="name"] .name').text(attachment.name);
        row.find('[data-column="mime"]').text(attachment.mime);
        row.find('[data-column="size"]').text(attachment.size);
    }

    public remove(attachment: Attachment): void {
        this.findRow(attachment.id).remove();
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
