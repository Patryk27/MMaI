import { Input } from './Input';

export class Select extends Input {

    /**
     * Changes list of all the options present in the select.
     * Resets select's value.
     */
    setOptions(options: Array<{ id: string, label: string }>): void {
        this.dom.element
            .find('option')
            .remove();

        options.forEach((option) => {
            $('<option>')
                .attr('value', option.id)
                .text(option.label)
                .appendTo(this.dom.element);
        });
    }

}
