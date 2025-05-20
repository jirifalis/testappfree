import {BaseClient} from "@/services/Api/BaseClient.ts";
import type {Resource} from "@/types/Resource.ts";

export class ResourceApiService extends BaseClient {
  async getList(): Promise<Resource[]> {
    return await this.request<Resource[]>('/admin/resources');
  }
}
