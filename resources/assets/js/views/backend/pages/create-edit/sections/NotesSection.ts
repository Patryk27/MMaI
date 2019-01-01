import { EventBus } from '../../../../../utils/EventBus';

export class NotesSection {

    private readonly dom: {
        notes: JQuery,
    };

    constructor(bus: EventBus, container: JQuery) {
        this.dom = {
            notes: container.find('[name="notes"]'),
        };

        this.dom.notes.on('change', () => {
            bus.emit('form::changed');
        });

        bus.on('tabs::changed', ({ currentSection }) => {
            if (currentSection === 'page-notes') {
                this.dom.notes.focus();
            }
        });
    }

    serialize(): string {
        // @ts-ignore
        return this.dom.notes.val();
    }

}
