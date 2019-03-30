import { FormError } from '@/ui/form/FormError';
import swal from 'sweetalert';

export class ApiError {

    public constructor(
        private readonly _type: string,
        private readonly _message: string,
        private readonly _payload?: any,
    ) {
    }

    public get type(): string {
        return this._type;
    }

    public get message(): string {
        return this._message;
    }

    public get payload(): any {
        return this._payload;
    }

    public get formErrors(): Array<FormError> | null {
        if (this.type === 'invalid-input' && this.payload) {
            const errors: Array<FormError> = [];

            for (const [fieldName, fieldErrors] of Object.entries(this.payload)) {
                (<Array<string>><undefined>fieldErrors).forEach((fieldError) => {
                    errors.push(new FormError(fieldName, fieldError));
                });
            }

            return errors;
        } else {
            return null;
        }
    }

    public display(title: string): void {
        // noinspection JSIgnoredPromiseFromCall
        swal({
            title: title,
            text: this.message,
            icon: 'error',
        });
    }

    public toString(): string {
        return this.message;
    }

}
