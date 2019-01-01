export class ApiError {

    private readonly type: string;
    private readonly message: string;
    private readonly payload: any;

    constructor(type: string, message: string, payload?: any) {
        this.type = type;
        this.message = message;
        this.payload = payload;
    }

    public getType(): string {
        return this.type;
    }

    public getMessage(): string {
        return this.message;
    }

    public getPayload(): any {
        return this.payload;
    }

    public toString(): string {
        return this.message;
    }

}
