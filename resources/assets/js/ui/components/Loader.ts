export class Loader {

    private readonly container: JQuery;

    constructor(selector: any) {
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
