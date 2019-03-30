export interface Page {
    id: number;
    title: string;
    lead: string | null;
    content: string;
    notes: string | null;

    type: string;
    status: string;

    created_at: string;
    updated_at: string;
}
