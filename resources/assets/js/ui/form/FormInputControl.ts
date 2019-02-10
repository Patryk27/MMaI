import { Input } from '@/ui/components/Input';
import { FormControl, FormError } from '@/ui/form';

export class FormInputControl implements FormControl {

    public constructor(
        public readonly id: string,
        public readonly input: Input,
    ) {

    }

    public serialize(): any {
        return {
            [this.id]: this.input.value,
        };
    }

    public focus(): void {
        this.input.focus();
    }

    public addError(error: FormError): void {
        if (error.field === this.id) {
            this.input.setFeedback('error', error.message);
        }
    }

    public clearErrors(): void {
        this.input.clearFeedback();
    }

}
