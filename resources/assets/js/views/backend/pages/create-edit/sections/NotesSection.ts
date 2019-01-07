import { EventBus } from '../../../../../utils/EventBus';

export class NotesSection {

    private notes: JQuery;

    constructor(bus: EventBus, container: JQuery) {
        this.notes = container.find('[name="notes"]');

        this.notes.on('change', () => {
            bus.emit('form::invalidate');
        });

        bus.on('tabs::changed', ({ currentSection }) => {
            if (currentSection === 'page-notes') {
                this.notes.focus();
            }
        });
    }

    serialize(): string {
        // @ts-ignore
        return this.dom.notes.val();
    }

}
