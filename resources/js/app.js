import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

import { createI18n } from "vue-i18n";
import localeMessages from "./vue-i18n-locales.generated";
import VueApexCharts from "vue3-apexcharts";
import '@vueup/vue-quill/dist/vue-quill.snow.css';

import { QuillEditor } from '@vueup/vue-quill'

import FontAwesomeIcon from './plugins/FontAwesome'

import MyMixins from "./plugins/mixins";

import 'vue3-toastify/dist/index.css';
import Vue3Toasity from 'vue3-toastify';

// const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'meow';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: async (name) => {
        const page = await resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue'));
        page.default.layout ??= DashboardLayout;
        return page;
    },
    setup({ el, App, props, plugin }) {
        const i18n = createI18n({
            locale: props.initialPage.props.locale, // user locale by props
            fallbackLocale: "en", // set fallback locale
            messages: localeMessages, // set locale messages
            legacy: false,
        });

       const app = createApp({ render: () => h(App, props) })
            .use(i18n)
            .mixin(MyMixins(props))
            .use(plugin)
            .use(Vue3Toasity, { autoClose: 3000 })
            .use(ZiggyVue, Ziggy)
            .use(VueApexCharts)
            app.component('FIcon', FontAwesomeIcon)
            app.component('QuillEditor', QuillEditor)
        return app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
