export interface SubscriptionItem {
  id: number;
  subscription_id: number;
  product_id: string;
  price_id: string;
  quantity: number;
  status: string;
  created_at: string;
  updated_at: string;
}

export interface Subscription {
  id: number;
  billable_id: number;
  billable_type: string;
  created_at: string;
  updated_at: string;
  ends_at: string | null;
  trial_ends_at: string | null;
  paused_at: string | null;
  status: "active" | "past_due" | "canceled";
  type: string;
  paddle_id: string;
  items: SubscriptionItem[];
}
