import swal from 'sweetalert';
import { Feedbackable } from '../concerns/Feedbackable';
import { Valuable } from '../concerns/Valuable';
import { Component } from './Component';

interface FormConfiguration {
    ajax?: boolean,
    form: JQuery,
    fields: {
        [name: string]: Component & Feedbackable & Valuable,
    },
}

interface SerializedForm {
    [name: string]: string,
}

/**
 * This class facilitates building simple forms - it makes it easy to group related components together and then handle
 * serialization, validation errors and so on.
 *
 * # Example
 *
 * ```javascript
 * const form = new Form({
 *   form: $('#my-form'),
 *
 *   components: {
 *     name: new Input($('#my-name')),
 *     gender: new Select($('#my-gender')),
 *   },
 * });
 * ```
 */
export class Form {

    // Handler to the form's container.
    private readonly form: JQuery;

    // Handlers to the form's components, keyed by the component's name.
    private readonly fields: {
        [name: string]: Component & Feedbackable & Valuable,
    };

    constructor(config: FormConfiguration) {
        this.form = config.form;
        this.fields = config.fields;

        // If the "ajax mode" is enabled, disable manual form's submission
        if (config.ajax) {
            this.form.on('submit', () => false);
        }
    }

    /**
     * Clears errors (feedbacks) from all the fields.
     * Should be called before the form is submitted, so that it does not show old (previous) error messages.
     */
    public clearErrors(): void {
        for (const [, field] of Object.entries(this.fields)) {
            field.removeFeedback();
        }
    }

    /**
     * Handles given error.
     *
     * If it's an error from the API, marks appropriate fields as invalid and adds appropriate feedback (e.g. "The name
     * is too short.")
     *
     * If it's not an API-error, a SweetAlert message is raised.
     */
    public processErrors(error: any) {
        if (error && error.getPayload && error.getPayload()) {
            let focused = false;

            for (const [fieldName, fieldErrors] of Object.entries(error.getPayload())) {
                const field = this.find(fieldName);

                // Add error message to the field
                field.addFeedback('invalid', (<Array<string>>fieldErrors).join(', '));

                // Focus on the first invalid field
                if (!focused) {
                    field.focus();
                    focused = true;
                }
            }
        } else {
            // noinspection JSIgnoredPromiseFromCall
            swal({
                title: 'An error occurred',
                text: error.toString(),
                icon: 'error',
            });
        }
    }

    /**
     * Serializes each form's component.
     */
    public serialize(): SerializedForm {
        let result: SerializedForm = {};

        for (const [name, component] of Object.entries(this.fields)) {
            result[name] = component.getValue();
        }

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
     * Returns given field.
     * Throws an exception if no such field exists.
     *
     * # Example
     *
     * ```javascript
     * alert(
     *   (<Input>form.getField('name')).getValue()
     * );
     * ```
     */
    public find<T extends Component & Feedbackable & Valuable>(name: string): T {
        if (!this.fields.hasOwnProperty(name)) {
            throw `Component [${name}] is not known.`;
        }

        return <T>this.fields[name];
    }

}
