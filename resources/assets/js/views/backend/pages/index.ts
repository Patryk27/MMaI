import { app } from '@/Application';
import { PagesGrid } from '@/modules/pages/PagesGrid';

app.onViewReady('backend.pages.index', () => {
    new PagesGrid(
        $('#pages-loader'),
        $('#pages-grid'),
    );
});
