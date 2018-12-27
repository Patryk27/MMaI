import InputComponent from './InputComponent';

export default class SelectComponent extends InputComponent {

    /**
     * Changes list of all the options present in the select.
     * Resets select's value.
     *
     * @param {array<{ id: string, label: string }>} options
     */
    setOptions(options) {
        this.$dom.el
            .find('option')
            .remove();

        options.forEach((option) => {
            $('<option>')
                .attr('value', option.id)
                .text(option.label)
                .appendTo(this.$dom.el);
        });
    }

}
