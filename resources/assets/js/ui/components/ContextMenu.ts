import { Tippy } from '@/utils/Tippy';

interface Action {
    title: string,
    cssClass: string,

    handle(): void;
}

interface Configuration {
    actions: { [name: string]: Action },
}

export class ContextMenu {

    constructor(private readonly config: Configuration) {
    }

    public run(anchor: JQuery): void {
        const menu = $('<div>');

        for (const [, action] of Object.entries(this.config.actions)) {
            $('<a>')
                .addClass('btn btn-sm ' + action.cssClass)
                .text(action.title)
                .on('click', action.handle)
                .appendTo(menu);
        }

        Tippy.once(anchor, {
            animation: 'shift-away',
            arrow: true,
            content: menu.get(0),
            interactive: true,
            placement: 'top',
        });
    }

}
