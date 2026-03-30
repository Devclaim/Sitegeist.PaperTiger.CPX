const esbuild = require('esbuild');
const extensibilityMap = require('@neos-project/neos-ui-extensibility/extensibilityMap.json');

const isWatch = process.argv.includes('--watch');

/** @type {import("esbuild").BuildOptions} */
const options = {
    logLevel: 'info',
    bundle: true,
    minify: true,
    sourcemap: 'linked',
    legalComments: 'linked',
    target: 'es2020',
    jsx: 'transform',
    jsxFactory: 'React.createElement',
    jsxFragment: 'React.Fragment',
    entryPoints: { Plugin: './src/index.js' },
    outdir: '../../Resources/Public/Neos.Ui',
    alias: extensibilityMap
};

if (isWatch) {
    esbuild.context(options).then((ctx) => ctx.watch());
} else {
    esbuild.build(options);
}
