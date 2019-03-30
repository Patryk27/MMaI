import { Input } from '@/ui/components';
import { FormControl, FormError, FormInputControl } from '@/ui/form';

export class NotesSection implements FormControl {

    public readonly id: string = 'notes';

    private readonly notes: FormInputControl;

    public constructor() {
        this.notes = new FormInputControl(
            'notes',
            Input.fromContainer($('#notes-form'), 'notes'),
        );
    }

    public addError(error: FormError): void {
        this.notes.addError(error);
    }

    public clearErrors(): void {
        this.notes.clearErrors();
    }

    public focus(): void {
        this.notes.focus();
    }

    public serialize() {
        return this.notes.serialize();
    }

}
