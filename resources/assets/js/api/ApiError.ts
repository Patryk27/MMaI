import { FormError } from '@/ui/form/FormError';

export class ApiError {

    constructor(
        private readonly _type: string,
        private readonly _message: string,
        private readonly _payload?: any,
    ) {
    }

    get type(): string {
        return this._type;
    }

    get message(): string {
        return this._message;
    }

    get payload(): any {
        return this._payload;
    }

    get formErrors(): Array<FormError> {
        const errors: Array<FormError> = [];

        for (const [fieldName, fieldErrors] of Object.entries(this._payload)) {
            (<Array<string>><undefined>fieldErrors).forEach((fieldError) => {
                errors.push(new FormError(fieldName, fieldError));
            });
        }

        return errors;
    }

    public toString(): string {
        return this._message;
    }

}
