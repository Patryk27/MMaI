import { Component } from './Component';

export class Checkbox extends Component {

    public isChecked(): boolean {
        return this.handle.is(':checked');
    }

}
