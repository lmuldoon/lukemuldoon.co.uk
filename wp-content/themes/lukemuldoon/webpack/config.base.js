/**
 * This holds the configuration that is being used for both development and production.
 * This is being imported and extended in the config.development.js and config.production.js files
 *
 * @since 1.1.0
 */
const magicImporter = require('node-sass-magic-importer'); // Add magic import functionalities to SASS
const MiniCssExtractPlugin = require('mini-css-extract-plugin'); // Extracts the CSS files into public/css
const BrowserSyncPlugin = require('browser-sync-webpack-plugin') // Synchronising URLs, interactions and code changes across devices
//const ImageMinimizerPlugin = require('image-minimizer-webpack-plugin'); // To optimize (compress) all images using
const FixStyleOnlyEntriesPlugin = require("webpack-fix-style-only-entries");
const { WebpackManifestPlugin } = require('webpack-manifest-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const webpack = require('webpack');

const path = require('path');

module.exports = (projectOptions) => {

    /**
     * CSS Rules
     */
    const cssRules = {
        test: projectOptions.projectCss.rules.test,
        use: [
            MiniCssExtractPlugin.loader, // Creates `style` nodes from JS strings
            { // Translates CSS into CommonJS
                loader: "css-loader", 
                options: {
                    url: false
                }
            },
            { // loads the PostCSS loader
                loader: "postcss-loader",
                options: require(projectOptions.projectCss.postCss)(projectOptions)
            },
            { // Compiles Sass to CSS
                loader: 'sass-loader',
                options: {
                    sassOptions: {
                        importer: magicImporter()
                    } // add magic import functionalities to sass
                }
            }
        ],
    };

    /**
     * JavaScript rules
     */
    const jsRules = {
        test: projectOptions.projectJs.rules.test,
        include: projectOptions.projectJsPath,
        use: 'babel-loader' // Configurations in "webpack/babel.config.js"
    };

    const imageRules = {
        test: projectOptions.projectImages.rules.test,
        use: [{
            loader: 'ignore-loader', // ignore images in css
        }, ],
    }

    /**
     * Font rules
     */
    const fontRules = {
        test: projectOptions.projectFonts.rules.test,
        use: [{
            loader: 'file-loader', 
            options: {
                name: f => {
                    let dirNameInsideAssets = path.relative(path.join(__dirname, '../assets', 'src'), path.dirname(f));
                    return `${dirNameInsideAssets}/[name].[ext]`;
                },
                publicPath: '../'
            }
        }, ],
    }

    /**
     * Optimization rules
     */
    const optimizations = {
        splitChunks: {
            cacheGroups: {
                styles: {
                    name: "screen",
                    type: "css/mini-extract",
                    chunks: (chunk) => {
                        return chunk.name !== "print";
                    },
                    enforce: true,
                },
            },
        }
    };

    /**
     * Plugins
     */
    const plugins = [
        new MiniCssExtractPlugin({ // Extracts CSS files
            filename: projectOptions.projectCss[ 'filename' + (process.env.NODE_ENV !== 'production' ? '_dev' : '') ]
        }),
        new CleanWebpackPlugin(),
        new FixStyleOnlyEntriesPlugin(),
        new WebpackManifestPlugin({
            publicPath: 'assets/public',
        }),
        new webpack.DefinePlugin({
            __VUE_OPTIONS_API__: true,
            __VUE_PROD_DEVTOOLS__: false,
        })
    ];
    // Add browserSync to plugins if enabled
    if (projectOptions.browserSync.enable === true) {
        const browserSyncOptions = {
            files: projectOptions.browserSync.files,
            host: projectOptions.browserSync.host,
            port: projectOptions.browserSync.port,
            ghostMode: projectOptions.browserSync.ghostMode,
        }
        if (projectOptions.browserSync.mode === 'server') {
            Object.assign(browserSyncOptions, {
                server: projectOptions.browserSync.server
            })
        } else {
            Object.assign(browserSyncOptions, {
                proxy: projectOptions.browserSync.proxy
            })
        }
        plugins.push(new BrowserSyncPlugin(browserSyncOptions, {
            reload: projectOptions.browserSync.reload,
            injectCss: projectOptions.browserSync.injectCss,
        }))
    }

    return {
        cssRules: cssRules,
        jsRules: jsRules,
        imageRules: imageRules,
        fontRules: fontRules,
        optimizations: optimizations,
        plugins: plugins,
    }
}