export class FilePicker {

    public run(): Promise<File> {
        return new Promise((resolve, reject) => {
            const form = $('<form>');

            $('<input>')
                .attr('type', 'file')
                .appendTo(form);

            const fileInput = form.find('input');

            form.on('submit', () => {
                const files = fileInput.prop('files');

                if (files.length === 0) {
                    reject();
                } else {
                    resolve(files[0]);
                }
            });

            fileInput.on('change', () => {
                form.submit();
            });

            fileInput.click();
        });
    }

}
