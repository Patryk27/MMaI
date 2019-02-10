import { app } from '@/Application';
import { PagesFilters } from '@/components/pages/PagesFilters';
import { PagesTable } from '@/components/pages/PagesTable';

app.onViewReady('backend.pages.index', () => {
    const pagesFilters = new PagesFilters({
        dom: {
            container: $('#pages-filters'),
        },

        events: {
            onChange() {
                pagesTable.refresh(pagesFilters.get);
            },
        },
    });

    const pagesTable = new PagesTable({
        dom: {
            loader: $('#pages-loader'),
            table: $('#pages-table'),
        },
    });

    pagesTable.refresh(pagesFilters.get);
});
