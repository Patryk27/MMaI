import { Attachment } from '@/api/attachments/Attachment';

export class AttachmentsTable {

    private readonly rowTemplate: JQuery;

    constructor(private readonly table: JQuery) {
        this.rowTemplate = table.find('.template');
    }

    public onAttachment(actionName: string, actionHandler: (payload: any) => void): void {
        this.table.on('click', `[data-action=${actionName}]`, (evt) => {
            const row = $(evt.target).closest('tr');

            actionHandler({
                attachment: row.data('attachment'),
            });

            evt.stopPropagation();
        });
    }

    public add(attachment: Attachment): void {
        const row = this.rowTemplate.clone();

        row.data('attachment', attachment);
        row.find('[data-column="id"]').text(attachment.id);
        row.find('[data-column="name"] .name').text(attachment.name);
        row.find('[data-column="mime"]').text(attachment.mime);
        row.find('[data-column="size"]').text(attachment.size);

        row.removeClass('template');
        row.appendTo(this.table.find('tbody'));
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
