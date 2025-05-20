export class BaseClient {
  private readonly baseUrl: string;

  constructor(baseUrl: string | null = null) {
    this.baseUrl = baseUrl ?? import.meta.env.VITE_APP_API_URL;
  }

  async request<T>(endpoint: string, method: string = 'GET', body?: object): Promise<T> {
    try {
      const options: RequestInit = {
        method,
        headers: {
          'Content-Type': 'application/json'
        }
      };

      if (body) {
        options.body = JSON.stringify(body);
      }

      const response = await fetch(`${this.baseUrl}${endpoint}`, options);
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      const data = await response.json();
      return data.data as T;

    } catch (error) {
      if (error instanceof Error) {
        throw new Error(`Failed to fetch data: ${error.message}`);
      }
      throw new Error('An unknown error occurred');
    }
  }
}
