import type {Group} from "@/types/Group.ts";

export interface User {
  id: number;
  name: string;
  extra: string;
  groups: Group[];
}