import { ApiClient } from '@/modules/core/ApiClient';
import { GridResponse } from '@/modules/grid/model/GridResponse';
import { GridSchema } from '@/modules/grid/model/GridSchema';
import { GridConfiguration } from '@/modules/grid/ui/GridConfiguration';
import { GridFilters } from '@/modules/grid/ui/GridFilters';
import { GridTable } from '@/modules/grid/ui/GridTable';
import { Loader } from '@/ui/components/Loader';

export class Grid<Item> {
    private readonly loader: Loader;
    private schema: GridSchema | null;
    private filters: GridFilters<Item> | null;
    private table: GridTable<Item> | null;

    public constructor(private readonly config: GridConfiguration<Item>) {
        this.loader = new Loader(config.dom.loader);
        this.loader.show();

        this.initialize().catch(window.onerror);
    }

    private async initialize(): Promise<void> {
        // Load schema from the API
        this.schema = await ApiClient.get<GridSchema>({
            url: this.config.http.url,
        });

        // Initialize filters
        this.filters = new GridFilters<Item>({
            gridSchema: this.schema,
            gridConfig: this.config,
        });

        // Initialize table
        this.table = new GridTable<Item>({
            gridSchema: this.schema,
            gridConfig: this.config,

            refresh: async (sorting, pagination) => {
                this.loader.show();

                try {
                    return await ApiClient.post<GridResponse<Item>>({
                        url: this.config.http.url,

                        data: {
                            filters: this.filters.get(),
                            sorting,
                            pagination,
                        },
                    });
                } finally {
                    this.loader.hide();
                }
            },
        });
    }
}


