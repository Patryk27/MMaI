import { Input } from './Input';

export class Select extends Input {

    public set options(options: Array<{ id: number | string, label: string }>) {
        const value = this.value;

        this.handle
            .find('option')
            .remove();

        options.forEach((option) => {
            $('<option>')
                .attr('value', option.id)
                .text(option.label)
                .appendTo(this.handle);
        });

        this.value = value;
    }

    public static fromContainer(container: any, name: string): Select {
        return new Select($(container).find(`[name='${name}']`));
    }

}
