import { Attachment } from '@/modules/attachments/Attachment';
import { AttachmentsFacade } from '@/modules/attachments/AttachmentsFacade';
import { FilePicker, ProgressBar } from '@/ui/components';
import { Modal } from '@/ui/components/Modal';

export class UploadAttachmentModal {

    private readonly modal: Modal;
    private readonly progressBar: ProgressBar;
    private readonly filePicker: FilePicker;

    public constructor(modal: JQuery) {
        this.modal = new Modal(modal);
        this.progressBar = new ProgressBar(modal.find('.progress'));
        this.filePicker = new FilePicker();
    }

    public async show(): Promise<Attachment | null> {
        const file = await this.filePicker.run();

        if (!file) {
            return null;
        }

        this.modal.show();
        this.progressBar.progress = 0;

        try {
            return await this.upload(file);
        } finally {
            this.modal.hide();
        }
    }

    private upload(file: File): Promise<Attachment> {
        return new Promise((resolve, reject) => {
            const response = AttachmentsFacade.create(file);

            response.onProgress((progress) => {
                this.progressBar.progress = progress.uploadedPercentage;
            });

            response.onFailure((error) => {
                reject(error);
            });

            response.onSuccess((attachment) => {
                resolve(attachment);
            });
        });
    }

}
