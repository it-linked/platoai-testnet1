import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/css/app.css',
        'resources/css/frontend.scss',
        'resources/js/app.js',
        'resources/js/web3-onboard.js',  // Fix the comma here
      ],
      refresh: true,
    }),
  ],
  define: {
    'process.env': process.env,
  },
  optimizeDeps: {
    include: [
      'web3',
      '@web3-onboard/core',
      '@web3-onboard/metamask',
	  '@web3-onboard/ledger',
      '@web3-onboard/injected-wallets',
	  '@web3-onboard/trezor/connect'
      // Add any other dependencies you want to include here
    ],
  },
  build: {
    target: 'es2021',
  },
});
