/**
 * This interface defines a component that has a readable value (e.g. "<input>" or "<select>").
 */
export interface Valuable {

    /**
     * Returns component's value.
     */
    getValue(): any;

    /**
     * Changes component's value.
     */
    setValue(value: any): void;

}
