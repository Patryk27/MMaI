import { Component } from './Component';

export class Checkbox extends Component {

    public get checked(): boolean {
        return this.handle.is(':checked');
    }

}
