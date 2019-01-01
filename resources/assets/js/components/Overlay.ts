export class Overlay {

    private dom: {
        overlay: JQuery,
    };

    constructor() {
        this.dom = {
            overlay: $('#overlay'),
        };
    }

    public show(): void {
        this.dom.overlay.addClass('visible');
    }

    public hide(): void {
        this.dom.overlay.removeClass('visible');
    }

}
