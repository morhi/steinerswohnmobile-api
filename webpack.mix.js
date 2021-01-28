let mix = require('laravel-mix')
let fs = require('fs')

console.log(process.env)

// Build the app
mix
    .vue({version: 3})
    .js('resources/js/app.js', 'public/dist/')

    .webpackConfig({
        output: {
            publicPath: 'https://localhost:8080/'
        },
        devServer: {
            hot: true,
            host: 'localhost',
            port: 8080,
            https: {
                key: fs.readFileSync('/Users/mhildebrandt/LocalCA/wild.localhost.key'),
                cert: fs.readFileSync('/Users/mhildebrandt/LocalCA/wild.localhost.crt'),
                ca: fs.readFileSync('/Users/mhildebrandt/LocalCA/rootCA.crt')
            }
        }
    })
