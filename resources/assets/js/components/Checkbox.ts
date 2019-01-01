import { Component } from './Component';

export class Checkbox extends Component {

    public isChecked(): boolean {
        return this.dom.element.is(':checked');
    }

}
