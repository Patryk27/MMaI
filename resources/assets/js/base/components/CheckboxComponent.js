import Component from './Component';

export default class CheckboxComponent extends Component {

    /**
     * Returns `true` if this checkbox is currently checked.
     *
     * @returns {boolean}
     */
    isChecked() {
        return this.$dom.el.is(':checked');
    }

}