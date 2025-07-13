import type { route as routeFn } from 'ziggy-js';

declare global {
  const route: typeof routeFn;

  interface Window {
    Paddle?: {
      Checkout?: {
        open: (options: {
          items: object[];
          customer: {
            email: string;
          };
          settings: {
            successUrl?: string;
            allowLogout?: boolean;
          };
        }) => void;
      };
    };
  }
}
