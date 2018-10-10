import clipboard from 'clipboard-polyfill';
import swal from 'sweetalert';

export default class Clipboard {

    /**
     * Writes text to the clipboard.
     *
     * @param {string} text
     * @returns {Promise<void>}
     */
    static async writeText(text) {
        try {
            await clipboard.writeText(text);
        } catch (_) {
            // noinspection JSIgnoredPromiseFromCall
            swal({
                title: 'Failed to copy URl',
                text: 'Sorry - it seems your browser does not support copying text.',
                icon: 'error',
            });
        }
    }

}
