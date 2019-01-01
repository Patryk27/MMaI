import clipboard from 'clipboard-polyfill';
import swal from 'sweetalert';

export class Clipboard {

    static async writeText(text: string): Promise<void> {
        try {
            await clipboard.writeText(text);
        } catch (_) {
            // noinspection JSIgnoredPromiseFromCall
            swal({
                title: 'Failed to copy URL', // @todo error message should be customizable
                text: 'Sorry - it seems your browser does not support copying text.',
                icon: 'error',
            });
        }
    }

}
