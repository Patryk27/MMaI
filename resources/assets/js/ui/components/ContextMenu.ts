import { Tippy } from '@/utils/Tippy';

interface Action {
    title: string,
    class: string,

    handle(): Promise<any> | void;
}

interface Configuration {
    actions: { [name: string]: Action },
}

export class ContextMenu {

    constructor(private readonly config: Configuration) {
    }

    public show(anchor: JQuery): void {
        const menu = $('<div>');

        for (const [, action] of Object.entries(this.config.actions)) {
            $('<a>')
                .addClass('btn btn-sm ' + action.class)
                .text(action.title)
                .on('click', () => {
                    const result = action.handle();

                    if (result instanceof Promise) {
                        result.catch(window.onerror);
                    }
                })
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
