import {BaseClient} from "@/services/Api/BaseClient.ts";
import type {User} from "@/types/User.ts";

export class UserApiService extends BaseClient{

  async getList(): Promise<User[]> {
    return await this.request<User[]>('/admin/users');
  }
  async getById(id: number): Promise<User> {
    return await this.request<User>('/admin/user/'+id.toString());
  }
  async updateName(id: number, name: string): Promise<User> {
    return await this.request<User>('/admin/user/'+id.toString(),'POST', {name:name});
  }
  async setGroups(id: number, groups: number[]): Promise<User> {
    return await this.request<User>('/admin/user/' + id.toString() + '/groups', 'POST', {groups: groups.join(',')});
  }

}
