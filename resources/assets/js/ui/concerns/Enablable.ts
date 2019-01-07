/**
 * This interface defines a component that can be enabled and disabled.
 */
export interface Enablable {

    /**
     * Enables the component.
     */
    enable(): void;

    /**
     * Disables the component.
     */
    disable(): void;

    /**
     * Enables / disables the component depending on the parameter.
     */
    setIsEnabled(isEnabled: boolean): void;

}
