import { ApiClient } from '@/modules/core/ApiClient';
import { GridResponse } from '@/modules/grid/model/GridResponse';
import { GridSchema } from '@/modules/grid/model/GridSchema';
import { GridFilters } from '@/modules/grid/ui/GridFilters';
import { GridTable } from '@/modules/grid/ui/GridTable';
import { Loader } from '@/ui/components/Loader';

export interface GridConfiguration<Item> {
    dom: {
        loader: JQuery,
        grid: JQuery,
    },

    http: {
        itemsUrl: string,
        schemaUrl: string,
    },

    fields: GridFieldsConfiguration<Item>,
}

export interface GridFieldsConfiguration<Item> {
    [fieldName: string]: GridFieldConfiguration<Item>,
}

export interface GridFieldConfiguration<Item> {
    render(item: Item): JQuery;
}

export class Grid<Item> {
    private readonly loader: Loader;
    private definition: GridSchema | null;
    private filters: GridFilters | null;
    private table: GridTable<Item> | null;

    public constructor(
        private readonly config: GridConfiguration<Item>,
    ) {
        this.loader = new Loader(config.dom.loader);
        this.loader.show();

        ApiClient.get<GridSchema>({ url: config.http.schemaUrl })
            .then((definition) => {
                this.definition = definition;

                this.filters = new GridFilters();

                this.table = new GridTable(
                    $('<div>').appendTo(config.dom.grid),
                    definition.fields,
                    config.fields,
                );

                this.refreshOrFail();
            })
            .catch(window.onerror);
    }

    private async refresh(): Promise<void> {
        this.loader.show();

        const response = await ApiClient.get<GridResponse<Item>>({
            url: this.config.http.itemsUrl,
        });

        this.loader.hide();

        this.table.refresh(response.items);
    }

    private refreshOrFail(): void {
        this.refresh().catch(window.onerror);
    }
}


