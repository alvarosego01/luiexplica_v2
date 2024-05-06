// import { defineConfig } from 'vite';
// import packageJson from './package.json';

// const { entry, output } = packageJson.config.js;

// export default defineConfig(({ mode }) => {
//   return {
//     build: {
//       rollupOptions: {
//         input: entry,
//         output: {
//           dir: output,
//           entryFileNames: '[name].js',
//         },
//       },
//       sourcemap: mode === 'development',
//       minify: mode === 'production',
//     },
//   };
// });


import { defineConfig } from 'vite';
import fs from 'fs';
import path from 'path';

// Ruta al directorio de los archivos JS de entrada
const entryDir = path.resolve(__dirname, 'src/js');

// Lee los archivos en el directorio de entrada y crea un objeto de entrada para Vite
const input = fs.readdirSync(entryDir)
  .reduce((entries, file) => {
    if (file.endsWith('.js')) {
      const name = path.basename(file, '.js');
      entries[name] = path.resolve(entryDir, file);
    }
    return entries;
  }, {});

export default defineConfig(({ mode }) => {
  return {
    build: {
      rollupOptions: {
        input,
        output: {
          dir: 'assets/js',
          entryFileNames: '[name].js',
        },
      },
      sourcemap: mode === 'development',
      minify: mode === 'production',
    },
  };
});
