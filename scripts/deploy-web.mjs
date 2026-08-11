#!/usr/bin/env node
// Copies an Expo Router web export into this app's public/ folder, so the
// mobile app (/), the admin panel (/admin), and the API (/api) all deploy
// from one Laravel document root — one origin, no separate port for the
// mobile app in production.
//
// sport-mobile is a separate repository, so this does NOT assume it's
// checked out next to sport-backend. Instead it looks for the export in
// (first match wins):
//
//   1. $MOBILE_DIST_DIR, if set
//   2. ./mobile-dist              <- copy sport-mobile's `dist/` folder's
//                                    *contents* here when the two repos
//                                    aren't both available (e.g. you built
//                                    the mobile app elsewhere/in its own
//                                    CI and are dropping the result in)
//   3. ../sport-mobile/dist       <- convenience for local dev when both
//                                    repos happen to be checked out
//                                    side by side
//
// Usage:
//   npm run deploy:web
//
// Safe to re-run: it never touches Laravel's own front controller,
// rewrite rules, the admin panel's Vite build, or the public storage
// symlink — see PROTECTED below.

import { existsSync, readdirSync, cpSync } from 'node:fs';
import { fileURLToPath } from 'node:url';
import path from 'node:path';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const DEST = path.resolve(__dirname, '../public');

const candidates = [
  process.env.MOBILE_DIST_DIR && path.resolve(process.env.MOBILE_DIST_DIR),
  path.resolve(__dirname, '../mobile-dist'),
  path.resolve(__dirname, '../../sport-mobile/dist'),
].filter(Boolean);

const SRC = candidates.find(existsSync);

// Never let the mobile export overwrite these — they belong to Laravel
// itself. (The mobile export doesn't currently produce anything with
// these names, but this is the backstop if that ever changes.)
const PROTECTED = new Set(['index.php', '.htaccess', 'build', 'storage', 'robots.txt']);

if (!SRC) {
  console.error('No web export found. Tried:');
  for (const c of candidates) console.error(`  ${c}`);
  console.error(
    '\nBuild the mobile app first (in sport-mobile: npm run build:web), then either:\n' +
      '  - copy the contents of sport-mobile/dist into sport-backend/mobile-dist, or\n' +
      '  - set MOBILE_DIST_DIR to point at the dist folder directly.'
  );
  process.exit(1);
}

let copied = 0;

for (const entry of readdirSync(SRC)) {
  if (PROTECTED.has(entry)) {
    console.warn(`Skipping "${entry}" — reserved for Laravel, not overwritten.`);
    continue;
  }

  cpSync(path.join(SRC, entry), path.join(DEST, entry), { recursive: true, force: true });
  copied++;
}

console.log(`Copied ${copied} item(s) from\n  ${SRC}\ninto\n  ${DEST}`);
