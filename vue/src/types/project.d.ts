import { ProjectUser } from "@/types/project-user";

export interface Project {
  id: number;
  name: string;
  owner?: ProjectUser;
  users: ProjectUser[];
  role: string;
  is_current: boolean;
  created_at: string;
  updated_at: string;

  [key: string]: unknown;
}
