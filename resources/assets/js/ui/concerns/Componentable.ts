export interface Componentable {

    focus(): void;

    on(event: string, handler: (...args: any) => void): void;

}
