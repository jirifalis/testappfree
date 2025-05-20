import {BaseClient} from "@/services/Api/BaseClient.ts";
import type {Group} from "@/types/Group.ts";

export class GroupApiService extends BaseClient {

  async getList(): Promise<Group[]> {
    return await this.request<Group[]>('/admin/groups');
  }

  async getById(id: number): Promise<Group> {
    return await this.request<Group>('/admin/group/' + id.toString());
  }

  async updateName(id: number, name: string): Promise<Group> {
    return await this.request<Group>('/admin/group/' + id.toString(), 'POST', {name: name});
  }

  async create(name: string): Promise<Group> {
    return await this.request<Group>('/admin/group', 'POST', {name: name});

  }

  async setResources(id: number, resources: number[]): Promise<Group> {
    return await this.request<Group>('/admin/group/' + id.toString() + '/resources', 'POST', {resources: resources.join(',')});
  }

}
