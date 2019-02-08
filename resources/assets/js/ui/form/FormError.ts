export class FormError {

    constructor(
        private readonly _field: string,
        private readonly _message: string,
    ) {

    }

    get field(): string {
        return this._field;
    }

    get message(): string {
        return this._message;
    }
}
