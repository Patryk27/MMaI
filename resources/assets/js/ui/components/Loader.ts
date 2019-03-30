export class Loader { // @todo LoaderComponent?

    private readonly container: JQuery;

    public constructor(selector: any) {
        this.container = $(selector);
        this.container.addClass('loader');

        $('<div>')
            .addClass('loader')
            .addClass('loader-' + this.container.data('loader-type'))
            .appendTo(this.container);

        $('<div>')
            .addClass('loader-overlay')
            .appendTo(this.container);
    }

    public show(): void {
        this.container.addClass('active');
    }

    public hide(): void {
        this.container.removeClass('active');
    }

}
