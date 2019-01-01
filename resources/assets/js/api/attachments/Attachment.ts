export class Attachment {

    public id: number;
    public name: string;
    public mime: string;
    public size: number | string;
    public url: string;

    // These two fields only exist in the UI:
    public status: string;
    public statusPayload: any;

}
