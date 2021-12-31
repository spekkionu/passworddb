import { App } from '@inertiajs/inertia-svelte'
import Layout from './components/Layout.svelte'
const el = document.getElementById('app')

new App({
    target: el,
    props: {
        initialPage: JSON.parse(el.dataset.page),
        // resolveComponent: name => require(`./Pages/${name}.svelte`),
        resolveComponent: name => import(`./Pages/${name}.svelte`)
            .then(page => {
                if (page.layout === undefined) {
                    page.layout = Layout
                }
                return page
            }),
    },
})
