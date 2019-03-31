import { GridQueryFilter } from '@/modules/grid/query/GridQueryFilter';
import { GridQueryFilterModal } from '@/modules/grid/query/GridQueryFilter.modal';
import { GridFilterComponent } from '@/modules/grid/ui/filters/GridFilter.component';
import { GridFiltersConfiguration } from '@/modules/grid/ui/filters/GridFilters.configuration';

export class GridFiltersComponent<Item> {
    private readonly filters: JQuery;
    private readonly filterModels: Array<GridQueryFilter> = [];
    private readonly filterComponents: Array<GridFilterComponent> = [];
    private readonly filterModal: GridQueryFilterModal;

    public constructor(private readonly config: GridFiltersConfiguration<Item>) {
        const wrapper =
            $('<div>')
                .addClass('grid-filters')
                .appendTo(config.gridConfig.dom.grid);

        this.filters = $('<div>').appendTo(wrapper);

        $('<button>')
            .addClass('btn btn-primary btn-sm grid-add-filter')
            .html('Create filter&nbsp;&nbsp;<i class="fa fa-plus"></i>')
            .on('click', () => {
                this.createFilter();
            })
            .appendTo(wrapper);

        this.filterModal = new GridQueryFilterModal(
            $('#grid-filter-modal'),
            config.gridSchema,
        );
    }

    /**
     * Returns all the filters applied by the user.
     */
    public get(): Array<GridQueryFilter> {
        return this.filterModels;
    }

    /**
     * Refreshers list of applied filters.
     * Should be called when fiddling with filters.
     */
    private refresh(): void {
        this.filters.empty();

        this.filterComponents.forEach((filter) => {
            this.filters.append(
                filter.render(),
            );
        });
    }

    /**
     * Opens the "create filter" modal and lets user add a new filter to the list.
     */
    private createFilter(): void {
        this.filterModal
            .show(null)
            .then((filter) => {
                if (filter) {
                    const filterComponent = new GridFilterComponent({
                        filter,

                        onEdit: (filter) => {
                            this.editFilter(filter);
                        },

                        onDelete: (filter) => {
                            this.deleteFilter(filter);
                        },
                    });

                    this.filterModels.push(filter);
                    this.filterComponents.push(filterComponent);
                    this.refresh();
                }
            });
    }

    /**
     * Opens the "edit filter" modal and lets user edit an existing filter.
     */
    private editFilter(filter: GridQueryFilter): void {
        const oldFilter = filter;

        this.filterModal
            .show(oldFilter)
            .then((newFilter) => {
                const filterIdx = this.filterModels.indexOf(oldFilter);

                if (filterIdx > -1) {
                    this.filterModels[filterIdx] = newFilter;
                    this.filterComponents[filterIdx].filter = newFilter;
                    this.refresh();
                }
            });
    }

    /**
     * Removes given filter.
     */
    private deleteFilter(filter: GridQueryFilter): void {
        const filterIdx = this.filterModels.indexOf(filter);

        if (filterIdx > -1) {
            this.filterModels.splice(filterIdx, 1);
            this.filterComponents.splice(filterIdx, 1);
            this.refresh();
        }
    }
}
