import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

const appearance = localStorage.getItem('appearance') || 'system';
if (appearance === 'dark' || (appearance === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
  document.documentElement.classList.add('dark');
} else {
  document.documentElement.classList.remove('dark');
}

window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
  if (appearance === 'system') {
    if (e.matches) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
  }
});
