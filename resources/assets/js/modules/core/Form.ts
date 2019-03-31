export interface Form<E> {

    reset(entity: E | null): void;

    submit(): Promise<E | null>;

}
