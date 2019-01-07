import { Input } from './Input';

export class Select extends Input {

    /**
     * Changes list of all the select's options.
     * Resets currently selected option(s) to none.
     *
     * # Example
     *
     * ```javascript
     * select.setOptions([
     *   { id: 'foo', label: 'Foo' },
     *   { id: 'bar', label: 'Bar' },
     * ]);
     * ```
     */
    setOptions(options: Array<{ id: number | string, label: string }>): void {
        this.handle
            .find('option')
            .remove();

        options.forEach((option) => {
            $('<option>')
                .attr('value', option.id)
                .text(option.label)
                .appendTo(this.handle);
        });
    }

}
