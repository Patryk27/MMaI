import axios, { AxiosRequestConfig, AxiosResponse } from 'axios';
import { ApiError } from './ApiError';
import { ApiTrackedResponse } from './ApiTrackedResponse';

export class ApiConnector {

    public static async request<T>(request: AxiosRequestConfig): Promise<T> {
        try {
            return (await axios.request(request)).data;
        } catch (error) {
            console.error(`Request at [${request.method} ${request.url}] failed with following exception:`, error);

            if (error.hasOwnProperty('response')) {
                const response = error.response;

                switch (response.status) {
                    case 413:
                        ApiConnector.handleHttp413();
                        return;

                    case 422:
                        ApiConnector.handleHttp422(response);
                        return;

                    case 500:
                        ApiConnector.handleHttp500(response);
                        return;
                }
            }

            throw new ApiError('exception', error.toString(), error);
        }
    }

    public static trackedRequest<T>(request: AxiosRequestConfig): ApiTrackedResponse<T> {
        const trackedResponse = new ApiTrackedResponse<T>();

        request.onUploadProgress = (event) => {
            trackedResponse.fireProgress({
                uploadedBytes: event.loaded,
                totalBytes: event.total,
                uploadedPercentage: Math.floor(100 * event.loaded / event.total),
            });
        };

        ApiConnector.request<T>(request)
            .then((data) => {
                trackedResponse.fireSuccess(data);
            })
            .catch((error) => {
                trackedResponse.fireFailure(error);
            });

        return trackedResponse;
    }

    private static handleHttp413(): void {
        throw new ApiError('invalid-input', 'Supplied file was too big - please try a smaller one.');
    }

    private static handleHttp422({ data }: AxiosResponse): void {
        throw new ApiError('invalid-input', data.message, data.errors);
    }

    private static handleHttp500({ data }: AxiosResponse): void {
        throw new ApiError('exception', data.message);
    }

}
