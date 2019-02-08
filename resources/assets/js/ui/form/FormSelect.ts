import { Select } from '@/ui/components';
import { FormControl, FormError } from '@/ui/form';

export class FormSelect implements FormControl {

    public constructor(
        public readonly id: string,
        public readonly select: Select,
    ) {

    }

    public serialize(): any {
        return {
            [this.id]: this.select.value,
        };
    }

    public focus(): void {
        this.select.focus();
    }

    public addError(error: FormError): void {
        if (error.field === this.id) {
            this.select.setFeedback('error', error.message);
        }
    }

    public clearErrors(): void {
        this.select.clearFeedback();
    }

}
