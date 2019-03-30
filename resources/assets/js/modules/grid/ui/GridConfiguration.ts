export interface GridConfiguration<Item> {
    dom: {
        loader: JQuery,
        grid: JQuery,
    },

    http: {
        url: string,
    },

    fields: GridFieldsConfiguration<Item>,
}

export interface GridFieldsConfiguration<Item> {
    [fieldName: string]: GridFieldConfiguration<Item>,
}

export interface GridFieldConfiguration<Item> {
    render(item: Item): JQuery;
}
