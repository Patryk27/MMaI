export default class NotesSection {

    /**
     * @param {Bus} bus
     * @param {jQuery} $container
     */
    constructor(bus, $container) {
        this.$dom = {
            notes: $container.find('[name="notes"]'),
        };

        bus.on('tabs::changed', ({ activatedTabName }) => {
            if (activatedTabName === 'page-notes') {
                this.$dom.notes.focus();
            }
        });
    }

    /**
     * @returns {string}
     */
    serialize() {
        return this.$dom.notes.val();
    }

}
