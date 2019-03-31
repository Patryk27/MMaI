import { GridComponent } from '@/modules/grid/Grid.component';
import { Page } from '@/modules/pages/Page';
import moment from 'moment';

export class PagesGrid {
    private grid: GridComponent<Page>;

    public constructor(loader: JQuery, grid: JQuery) {
        this.grid = new GridComponent({
            dom: {
                loader,
                grid,
            },

            http: {
                url: '/pages/grid',
            },

            fields: {
                createdAt: {
                    render(page) {
                        const createdAt = moment(page.created_at);

                        return $('<span>')
                            .text(createdAt.fromNow())
                            .attr('title', createdAt.format('LLLL'));
                    },
                },

                status: {
                    render(page) {
                        // @ts-ignore
                        const statusName = {
                            'draft': 'Draft',
                            'published': 'Published',
                            'deleted': 'Deleted',
                        }[page.status];

                        // @ts-ignore
                        const statusClass = {
                            'draft': 'badge-warning',
                            'published': 'badge-success',
                            'deleted': 'badge-danger',
                        }[page.status];

                        return $('<span>')
                            .addClass(`badge badge-pill ${statusClass}`)
                            .text(statusName);
                    },
                },

                type: {
                    render(page) {
                        const typeNames = {
                            'page': 'Page',
                            'post': 'Post',
                        };

                        // @ts-ignore
                        const typeName = typeNames[page.type];

                        return $('<span>').text(typeName);
                    },
                },

                website: {
                    render(page) {
                        // @ts-ignore
                        return $('<span>').text(page.website.name);
                    },
                },
            },
        });
    }
}
