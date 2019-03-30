export class Tag {

    public id?: number;
    public websiteId: number;
    public name: string;

    public constructor(data: any) {
        this.id = data.id;
        this.websiteId = data.website_id;
        this.name = data.name;
    }

}
