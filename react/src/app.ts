import '../css/app.css';

import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';
import { initializeTheme } from './hooks/use-appearance';
import React from 'react'; // Needed for createElement

const appName = import.meta.env.VITE_APP_NAME || 'Vito';

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) => resolvePageComponent(`./pages/${name}.tsx`, import.meta.glob('./pages/**/*.tsx')),
  setup({ el, App, props }) {
    const root = createRoot(el);

    root.render(React.createElement(App, props));
  },
  progress: {
    color: '#554afa',
  },
}).then();

// This will set light / dark mode on load...
initializeTheme();
