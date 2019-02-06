import { ApiError } from '@/api/ApiError';
import swal from 'sweetalert';
import { Field } from './Field';

interface FormConfiguration {
    form: JQuery,
    fields: Array<Field>,
}

export class Form {

    private readonly form: JQuery;
    private readonly fields: Array<Field>;

    constructor(config: FormConfiguration) {
        this.form = config.form;
        this.form.on('submit', () => false);

        this.fields = config.fields;
    }

    public clearErrors(): void {
        this.fields.forEach((field) => {
            field.clearFeedback();
        });
    }

    public markErrors(error: ApiError) {
        let focused = false;

        for (const [fieldName, fieldErrors] of Object.entries(error.getPayload())) {
            const field = this.findMaybe(fieldName);

            if (!field) {
                continue;
            }

            field.setFeedback('invalid', (<Array<string>>fieldErrors).join(', '));

            if (!focused) {
                field.focus();
                focused = true;
            }
        }
    }

    public processErrors(error: any) {
        if (error && error.getPayload && error.getPayload()) {
            this.markErrors(error);
        } else {
            // noinspection JSIgnoredPromiseFromCall
            swal({
                title: 'An error occurred',
                text: error.toString(),
                icon: 'error',
            });
        }
    }

    public serialize(): object {
        let result = {};

        this.fields.forEach((field) => {
            Object.assign(result, field.serialize());
        });

        return result;
    }

    /**
     * Binds handler to given form's event.
     *
     * # Example
     *
     * ```javascript
     * form.on('change', () => {
     *   console.log('Form has been changed.');
     * });
     * ```
     */
    public on(event: string, handler: (...args: any) => void): void {
        this.form.on(event, handler);
    }

    /**
     * Binds handler to given field's event.
     *
     * # Example
     *
     * ```javascript
     * form.onField('name', 'change', () => {
     *   console.log('Name has been changed.');
     * });
     * ```
     */
    public onField(component: string, event: string, handler: (...args: any) => void): void {
        this.find(component).on(event, handler);
    }

    /**
     * Returns given field or `null` if no such one exists.
     */
    public findMaybe(name: string): Field | null {
        for (const field of this.fields) {
            if (field.getName() === name) {
                return field;
            }
        }

        return null;
    }

    /**
     * Returns given field.
     * Throws an exception if no such field exists.
     */
    public find(name: string): Field {
        const field = this.findMaybe(name);

        if (!field) {
            throw `Component [${name}] is not known.`;
        }

        return field;
    }

}
