import { FormError } from '@/ui/form/FormError';
import { FormControl } from './FormControl';

interface FormConfiguration {
    controls: Array<FormControl>,
}

export class Form {

    private readonly controls: Array<FormControl>;

    constructor(config: FormConfiguration) {
        this.controls = config.controls;
    }

    public addError(error: FormError): void {
        this.controls.forEach((control) => {
            control.addError(error);
        });
    }

    public addErrors(errors: Array<FormError>): void {
        errors.forEach((error) => {
            this.addError(error);
        });
    }

    public clearErrors(): void {
        this.controls.forEach((control) => {
            control.clearErrors();
        });
    }

    public find<T extends FormControl>(id: string): T | null {
        for (const control of this.controls) {
            if (control.id === id) {
                return <T>control;
            }
        }

        return null;
    }

    public serialize(): any {
        let result: any = {};

        this.controls.forEach((control) => {
            Object.assign(result, control.serialize());
        });

        return result;
    }

}
