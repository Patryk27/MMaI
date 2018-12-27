export default class OverlayComponent {

    constructor() {
        this.$dom = {
            overlay: $('#overlay'),
        };
    }

    show() {
        this.$dom.overlay.addClass('visible');
    }

    hide() {
        this.$dom.overlay.removeClass('visible');
    }

}
