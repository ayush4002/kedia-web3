// Copies the motion/QR libraries from node_modules into public/ so the site self-hosts them.
import { copyFileSync, mkdirSync } from 'node:fs';

const out = 'public/assets/vendor';
mkdirSync(out, { recursive: true });
for (const [from, to] of [
  ['node_modules/gsap/dist/gsap.min.js', 'gsap.min.js'],
  ['node_modules/gsap/dist/ScrollTrigger.min.js', 'ScrollTrigger.min.js'],
  ['node_modules/lenis/dist/lenis.min.js', 'lenis.min.js'],
  ['node_modules/qrcode-generator/qrcode.js', 'qrcode.js'],
]) {
  copyFileSync(from, `${out}/${to}`);
  console.log(`copied ${to}`);
}
