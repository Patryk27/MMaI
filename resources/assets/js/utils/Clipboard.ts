import clipboard from 'clipboard-polyfill';
import swal from 'sweetalert';

export class Clipboard {

    static async writeText(text: string): Promise<void> {
        try {
            await clipboard.writeText(text);
        } catch (error) {
            console.error('Clipboard operation failed:', error);

            await swal({
                title: 'Failed access clipboard',
                text: 'Sorry - it seems your browser does not support clipboard operations.',
                icon: 'error',
            });
        }
    }

}
