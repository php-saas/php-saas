import { LucideIcon } from 'lucide-react';
import { User } from '@/types/user';
import { Project } from '@/types/project';

export interface Auth {
  user: User;
}

export interface BreadcrumbItem {
  title: string;
  href: string;
}

export interface NavItem {
  title: string;
  href: string;
  onlyActivePath?: string;
  icon?: LucideIcon | null;
  isDisabled?: boolean;
  children?: NavItem[];
  hidden?: boolean;
  external?: boolean;
  redirect?: boolean;
}

export interface Configs {
  [key: string]: unknown;
}

export interface SharedData {
  auth: Auth;
  project_provider: {
    current?: Project;
    list?: Project[];
  };
  configs: Configs;
  status: string;
  flash?: {
    status: string;
    success: string;
    error: string;
    info: string;
    warning: string;
    data: unknown;
  };

  [key: string]: unknown;
}

export interface PaginatedData<TData> {
  data: TData[];
  links: PaginationLinks;
  meta: PaginationMeta;
}

export interface PaginationLinks {
  first: string | null;
  last: string | null;
  prev: string | null;
  next: string | null;
}

export interface PaginationMeta {
  current_page: number;
  current_page_url: string;
  from: number | null;
  path: string;
  per_page: number;
  to: number | null;
  total?: number;
  last_page?: number;
}
