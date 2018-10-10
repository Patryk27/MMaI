import tippy from 'tippy.js';

export default class Tippy {

    /**
     * Creates an opened Tippy tooltip that destroys itself after being closed.
     * Useful for providing feedback to actions which do not cause a page reload (e.g. copying something to clipboard).
     *
     * @param {*} target
     * @param {object} options
     */
    static once(target, options) {
        // Tippy does not handle well jQuery selectors - in such cases let's just transparently translate them onto raw
        // HTML nodes
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
