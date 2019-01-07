import tippy, { Props } from 'tippy.js';

export class Tippy {

    /**
     * Creates an opened Tippy tooltip that destroys itself after being closed.
     * Useful for providing feedback to actions which do not cause a page reload (e.g. copying something to clipboard).
     */
    static once(target: any, options: Props) {
        if (target instanceof jQuery) {
            target = target[0];
        }

        options.showOnInit = true;

        options.onHidden = () => {
            setTimeout(() => {
                createdTippy.instances[0].destroy();
            });
        };

        const createdTippy = tippy(target, options);
    }

}
