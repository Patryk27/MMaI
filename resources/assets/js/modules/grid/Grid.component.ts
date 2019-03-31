import { ApiClient } from '@/modules/core/ApiClient';
import { GridConfiguration } from '@/modules/grid/Grid.configuration';
import { GridResponse } from '@/modules/grid/response/GridResponse';
import { GridSchema } from '@/modules/grid/schema/GridSchema';
import { GridFiltersComponent } from '@/modules/grid/ui/filters/GridFilters.component';
import { GridTableComponent } from '@/modules/grid/ui/table/GridTable.component';
import { Loader } from '@/ui/components/Loader';

export class GridComponent<Item> {
    private readonly loader: Loader;
    private schema: GridSchema | null;
    private filtersComponent: GridFiltersComponent<Item> | null;
    private tableComponent: GridTableComponent<Item> | null;

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
        this.filtersComponent = new GridFiltersComponent<Item>({
            gridSchema: this.schema,
            gridConfig: this.config,
        });

        // Initialize table
        this.tableComponent = new GridTableComponent<Item>({
            gridSchema: this.schema,
            gridConfig: this.config,

            refresh: async (sorting, pagination) => {
                this.loader.show();

                try {
                    return await ApiClient.post<GridResponse<Item>>({
                        url: this.config.http.url,

                        data: {
                            filters: this.filtersComponent.get(),
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


