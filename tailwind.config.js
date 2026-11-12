/** @type {import('tailwindcss').Config} */
module.exports = {
  // Blade views (incl. inline scripts) and the site script, which toggles classes.
  content: ['./resources/views/**/*.blade.php', './public/assets/site.js', './app/**/*.php', './config/kedia.php'],
  theme: {
    extend: {
      colors: {
        // "The Jaipur Blueprint" palette
        navy: {
          DEFAULT: '#0E1520', // the site's existing navy
          900: '#0A0F17',
          800: '#0E1520',
          700: '#152033',
          600: '#1D2B42',
          500: '#2A3A55',
        },
        paper: {
          DEFAULT: '#F6F1E7',
          50: '#FBF8F2',
          100: '#F6F1E7',
          200: '#EDE5D4',
          300: '#E0D5BE',
        },
        brass: {
          DEFAULT: '#C8A24B',
          300: '#DEC48A',
          400: '#D3B263',
          500: '#C8A24B',
          600: '#A9863A',
          700: '#86692E',
        },
        terracotta: '#C2674B', // tiny hover details only
        ink: '#1B2433',
        status: {
          available: '#3F8F6B',
          hold: '#C8A24B',
          sold: '#9A8F80',
        },
      },
      fontFamily: {
        serif: ['Fraunces', 'Playfair Display', 'Georgia', 'serif'],
        sans: ['Inter', 'system-ui', 'sans-serif'],
        mono: ['"JetBrains Mono"', 'ui-monospace', 'monospace'],
      },
      letterSpacing: {
        label: '0.18em',
      },
      maxWidth: {
        site: '1440px',
      },
      borderRadius: {
        arch: '999px 999px 0 0',
      },
    },
  },
  plugins: [],
};
