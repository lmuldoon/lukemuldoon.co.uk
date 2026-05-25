module.exports = {
  purge: {
    enable: true,
    content: [
      __dirname + '/../assets/src/js/**/*',
      __dirname + '/../template-parts/**/**/*',
      __dirname + '/../page-templates/**/**/*',
      __dirname + '/../*.php',
    ]
  },
  important: true,
  darkMode: false, // or 'media' or 'class'
  theme: {
    colors: {
      transparent: 'var(--color-transparent)',
      white: 'var(--color-white)',
      chalk: 'var(--color-chalk)',
      paper: 'var(--color-paper)',
      coal: 'var(--color-coal)',
      lime: 'var(--color-lime)',
      'lime-deep': 'var(--color-lime-deep)',
      mist: 'var(--color-mist)',
      smoke: 'var(--color-smoke)',
      muted: 'var(--color-muted)',
      warning: 'var(--color-warning)',
      caution: 'var(--color-caution)',
      success: 'var(--color-good)',
    },
    screens: {
      'sm': '576px',
      'md': '768px',
      'lg': '1024px',
      'mobile-menu': '1000px',
      'xl': '1351px',
      '2xl': '1636px',
    },
    fontFamily: {
      body: ['Space Grotesk', '-apple-system', 'Helvetica Neue', 'sans-serif'],
      heading: ['Space Grotesk', '-apple-system', 'Helvetica Neue', 'sans-serif'],
      mono: ['JetBrains Mono', 'ui-monospace', 'monospace'],
    },
    fontWeight: {
      normal: 400,
      medium: 500,
      semibold: 600,
      bold: 700,
    },
    lineHeight: {
      'none': 1,
      'tight': 1.05,
      'snug': 1.25,
      'regular': 1.5,
    },
    variables: {
      DEFAULT: {
        size: {
          "300": 'clamp(0.7em, 0.66rem + 0.2vw, 0.8em)',
          "400": 'clamp(1rem, 0.964rem + 0.182vw, 1.125rem)',
          "500": 'clamp(1.09em, 1em + 0.47vw, 1.275em)',
          "600": 'clamp(1.37em, 1em + 0.8vw, 1.5em)',
          "700": 'clamp(1.5em, 1.45em + 0.78vw, 2.1em)',
          "800": 'clamp(2.14em, 1.74em + 1.99vw, 3.16em)',
          "900": 'clamp(2.67em, 2.0em + 3vw, 3.8em)',
          "1000": 'clamp(2.625rem, 1.6075rem + 5.0877vw, 6.25rem)',
          "headline": 'clamp(2em, calc(2em + 6.66vw), 192px)'
        },
        color: {
          white: '#FFFFFF',
          chalk: '#F5F3EE',
          paper: '#FBF9F4',
          coal: '#0F1115',
          lime: '#9FE000',
          'lime-deep': '#7DB400',
          mist: '#D9D6CE',
          smoke: '#403D38',
          muted: '#6A6560',
          warning: '#E54B2A',
          caution: '#C28A00',
          success: '#6FAE00'
        },
      },
      // '.container': {
      //   sizes: {
      //     medium: '1.5em',
      //   },
      // },
    },
  },
  variants: {
    extend: {

    },
  },
  plugins: [require('@mertasan/tailwindcss-variables')],
  corePlugins: {
    preflight: false
  }

};