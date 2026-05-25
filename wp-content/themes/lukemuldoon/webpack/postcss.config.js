/**
 * PostCSS configuration file
 * as configured under cssRules.use['postcss-loader'] in development.js and production.js
 *
 * @docs https://postcss.org/
 * @since 1.0.0
 */

 module.exports = (projectOptions) => {

    const postcssOptions = {};
    const plugins = [
        require('tailwindcss')({
            config: './webpack/tailwind.config.js'
        }),
        require('autoprefixer')(),
    ];

    if ( projectOptions.projectConfig.isWatchMode ) {
        plugins.push( 
            require('postcss-watch-folder')({
                folder: projectOptions.projectScssPath,
                main: projectOptions.projectCss.entry.screen
            }), 
        );
    }

    Object.assign( postcssOptions, {
        plugins: plugins
    } );

    return {
        postcssOptions: postcssOptions
    }
}
