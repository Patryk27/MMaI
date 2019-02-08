import { FormError } from '@/ui/form/FormError';

export interface FormControl {

    readonly id: string;

    addError(error: FormError): void;

    clearErrors(): void;

    focus(): void;

    serialize(): any;

}
