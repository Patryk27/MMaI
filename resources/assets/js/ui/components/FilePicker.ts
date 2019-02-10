export class FilePicker {

    public run(): Promise<File | null> {
        return new Promise((resolve) => {
            const form = $('<form>');

            $('<input>')
                .attr('type', 'file')
                .appendTo(form);

            const fileInput = form.find('input');

            form.on('submit', () => {
                const files = fileInput.prop('files');

                if (files.length === 0) {
                    resolve(null);
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
