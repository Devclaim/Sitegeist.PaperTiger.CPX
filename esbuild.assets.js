const esbuild = require('esbuild');

const isWatch = process.argv.includes('--watch');

/** @type {import("esbuild").BuildOptions[]} */
const builds = [
    {
        logLevel: 'info',
        bundle: true,
        minify: true,
        sourcemap: 'linked',
        legalComments: 'linked',
        target: 'es2020',
        entryPoints: {
            Backend: './Resources/Private/Assets/Backend.ts',
            Honeypot: './Resources/Private/Assets/Honeypot.ts',
            AsyncForm: './Resources/Private/Assets/AsyncForm.ts'
        },
        outdir: './Resources/Public/Scripts'
    },
    {
        logLevel: 'info',
        bundle: true,
        minify: true,
        sourcemap: false,
        entryPoints: {
            Backend: './Resources/Private/Assets/Backend.css'
        },
        outdir: './Resources/Public/Styles'
    }
];

async function run() {
    if (isWatch) {
        const contexts = await Promise.all(builds.map((options) => esbuild.context(options)));
        await Promise.all(contexts.map((ctx) => ctx.watch()));
        return;
    }

    await Promise.all(builds.map((options) => esbuild.build(options)));
}

run().catch((error) => {
    console.error(error);
    process.exit(1);
});
