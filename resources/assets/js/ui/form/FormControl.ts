import { FormError } from '@/ui/form';

export interface FormControl { // @todo FormField?

    readonly id: string;

    addError(error: FormError): void;

    clearErrors(): void;

    focus(): void;

    serialize(): any;

}
