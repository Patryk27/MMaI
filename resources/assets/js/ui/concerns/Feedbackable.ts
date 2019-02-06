/**
 * This interface defines a component that can have textual feedback (e.g. "The value you entered is wrong.")
 */
export interface Feedbackable {

    setFeedback(type: string, message: string): void;

    clearFeedback(): void;

}
