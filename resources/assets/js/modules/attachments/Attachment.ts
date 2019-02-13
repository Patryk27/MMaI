export class Attachment {

    public id: number;
    public name: string;
    public mime: string;
    public size: number;
    public sizeForHumans: string;
    public url: string;

    public constructor(data: any) {
        this.id = data.id;
        this.name = data.name;
        this.mime = data.mime;
        this.sizeForHumans = data.size_for_humans;
        this.url = data.url;
    }

}
