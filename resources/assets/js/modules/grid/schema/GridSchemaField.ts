export interface GridSchemaField {
    readonly id: string;
    readonly name: string;
    readonly type: string;
    readonly values: Array<string>;
    readonly sortable: boolean;
}
