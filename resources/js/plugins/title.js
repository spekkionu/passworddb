import {watch, ref} from 'vue'

export default {
    install: (app, options) => {
        const title = ref(document.title);
        const setTitle = (value) => {
            title.value = value
        };
        watch(title, value => console.log('title is set', title.value))
        // watchEffect(() => console.log('title is set', title.value))

        app.config.globalProperties.documentTitle = title;

        app.config.globalProperties.$title = setTitle;
    }
}
