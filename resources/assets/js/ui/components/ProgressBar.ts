export class ProgressBar {

    public constructor(private readonly handle: JQuery) {

    }

    set progress(progress: number) {
        this.handle.find('.progress-bar').css({ width: progress + '%' });
    }

}
