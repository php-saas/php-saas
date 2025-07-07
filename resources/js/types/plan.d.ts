export interface Plan {
  name: string;
  description: string;
  billing: 'monthly' | 'yearly';
  price: number;
  price_id: string;
  motivation_text: string;
  features: string[];
  options: {
    [key: string]: string;
  };
  checkout?: {
    transaction?: {
      id: string;
    };
    items?: object[];
    custom_data: object[];
    return_url: string;
    custom;
  };

  [key: string]: unknown; // This allows for additional properties...
}
