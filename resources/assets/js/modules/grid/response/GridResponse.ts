export interface GridResponse<Item> {
    readonly totalCount: number;
    readonly matchingCount: number;
    readonly items: Array<Item>;
}
