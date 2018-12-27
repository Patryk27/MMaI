import Component from './Component';

export default class CheckboxComponent extends Component {

    /**
     * @returns {boolean}
     */
    isChecked() {
        return this.$dom.el.is(':checked');
    }

}
