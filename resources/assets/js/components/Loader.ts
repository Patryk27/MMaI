export class Loader {

    private dom: {
        container: JQuery,
        loader: JQuery,
        overlay: JQuery,
    };

    constructor(selector: any) {
        const container = $(selector);
        container.addClass('loader');

        this.dom = {
            container,

            loader: $('<div>')
                .addClass('loader')
                .addClass('loader-' + container.data('loader-type'))
                .appendTo(container),

            overlay: $('<div>')
                .addClass('loader-overlay')
                .appendTo(container),
        };
    }

    public show(): void {
        this.dom.container.addClass('active');
    }

    public hide(): void {
        this.dom.container.removeClass('active');
    }

}
