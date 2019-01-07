/**
 * This interface defines a component that utilizes events.
 */
export interface Eventable {

    /**
     * Binds given handler to specified event.
     *
     * # Example
     *
     * ```javascript
     * component.on('click', () => {
     *   alert('I have been clicked!');
     * });
     * ```
     */
    on(event: string, handler: (...args: any) => void): void;

}
