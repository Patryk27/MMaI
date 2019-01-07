export class Overlay {

    private overlay: JQuery;

    constructor() {
        this.overlay = $('#overlay');
    }

    public show(): void {
        this.overlay.addClass('visible');
    }

    public hide(): void {
        this.overlay.removeClass('visible');
    }

}
