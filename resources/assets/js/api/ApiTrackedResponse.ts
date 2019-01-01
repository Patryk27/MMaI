import { ApiError } from './ApiError';

type ProgressEventHandler = (payload: ProgressEventPayload) => void;
type ProgressEventPayload = {
    uploadedBytes: number,
    totalBytes: number,
    uploadedPercentage: number,
};

type SuccessEventHandler<T> = (payload: SuccessEventPayload<T>) => void;
type SuccessEventPayload<T> = T;

type FailureEventHandler = (payload: FailureEventPayload) => void;
type FailureEventPayload = ApiError;

export class ApiTrackedResponse<Response> {

    private readonly eventHandlers: {
        progress?: ProgressEventHandler,
        success?: SuccessEventHandler<Response>,
        failure?: FailureEventHandler,
    } = {};

    public onProgress(fn: ProgressEventHandler): void {
        this.eventHandlers.progress = fn;
    }

    public fireProgress(payload: ProgressEventPayload): void {
        if (this.eventHandlers.progress) {
            this.eventHandlers.progress(payload);
        }
    }

    public onSuccess(fn: SuccessEventHandler<Response>): void {
        this.eventHandlers.success = fn;
    }

    public fireSuccess(payload: SuccessEventPayload<Response>): void {
        if (this.eventHandlers.success) {
            this.eventHandlers.success(payload);
        }
    }

    public onFailure(fn: FailureEventHandler): void {
        this.eventHandlers.failure = fn;
    }

    public fireFailure(payload: FailureEventPayload): void {
        if (this.eventHandlers.failure) {
            this.eventHandlers.failure(payload);
        }
    }

}
