const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/components/**/*.svelte',
        './resources/js/Pages/**/*.svelte',
    ],
    theme: {

        extend: {
            fontFamily: {
                sans: ['Inter var', ...defaultTheme.fontFamily.sans],
            },
            colors: {},
            container: {
                center: true,
                padding: '1rem',
                maxWidth: {
                    xs: 'none'
                }
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('tailwindcss-tables')(),
    ],
}
