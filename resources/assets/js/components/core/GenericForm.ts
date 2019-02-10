export interface GenericForm<Entity> {

    reset(entity: Entity): void;

    submit(): Promise<Entity | null>;

}
