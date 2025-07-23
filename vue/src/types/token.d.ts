export interface Token {
  id: number;
  name: string;
  abilities: string[];
  created_at: string;
  updated_at: string;

  [key: string]: unknown;
}
