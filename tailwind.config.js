/** @type {import('tailwindcss').Config} */
module.exports = {
  // Every Blade view (including inline <script> blocks that toggle classes).
  content: ['./resources/views/**/*.blade.php'],
  theme: {
    extend: {
      colors: {
        kedia: {
          navy: '#0E1520',
          navyLight: '#162132',
          paper: '#FAF8F5',
          lime: '#D4E845',
          gold: '#C8A24B',
          stone: '#E8E3D8',
        },
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
        serif: ['Playfair Display', 'Cormorant Garamond', 'serif'],
        mono: ['JetBrains Mono', 'monospace'],
      },
    },
  },
  plugins: [],
};
