import type {Permission} from "@/types/Permission.ts";

export interface Group {
  id: number;
  name: string;
  permissions: Permission[];
  extras: string;
}
