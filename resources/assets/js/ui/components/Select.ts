import { Input } from './Input';

export class Select extends Input {

    set options(options: Array<{ id: number | string, label: string }>) {
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

    public static fromContainer(container: any, name: string): Select {
        return new Select($(container).find(`[name='${name}']`));
    }

}
