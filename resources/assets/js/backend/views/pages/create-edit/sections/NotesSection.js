export default class NotesSection {

    /**
     * @param {Bus} bus
     * @param {jQuery} $container
     */
    constructor(bus, $container) {
        this.$dom = {
            notes: $container.find('[name="notes"]'),
        };
        
        // When user changes contents of the notes, mark form as "dirty"
        this.$dom.notes.on('change', () => {
            bus.emit('form::changed');
        });

        // When this tab is being activated, focus on the "Notes" textarea
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
