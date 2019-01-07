/**
 * This interface defines a component that can have textual feedback (e.g. "The value you entered is wrong.")
 */
export interface Feedbackable {

    /**
     * Adds a feedback to the input.
     *
     * # Example
     *
     * ```javascript
     * component.addFeedback('valid', 'This input is correct.');
     * component.addFeedback('invalid', 'This input is not correct.');
     * ```
     */
    addFeedback(type: string, message: string): void;

    /**
     * Deletes feedback from the input.
     */
    removeFeedback(): void;

}
