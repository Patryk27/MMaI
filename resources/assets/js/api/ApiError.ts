export class ApiError {

    constructor(
        private readonly type: string,
        private readonly message: string,
        private readonly payload?: any,
    ) {
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
