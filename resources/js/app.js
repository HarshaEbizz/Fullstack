import './bootstrap';
import '../../public/assets/vendors/js/vendor.bundle.base.js';
import '../../public/assets/vendors/chart.js/Chart.min.js';
import '../../public/assets/js/jquery.cookie.js'
import '../../public/assets/js/off-canvas.js';
import '../../public/assets/js/hoverable-collapse.js';
import '../../public/assets/js/misc.js';
import '../../public/assets/js/dashboard.js';
import '../../public/assets/js/todolist.js';

import "../../public/assets/vendors/js/vendor.bundle.base";


import { createApp } from 'vue/dist/vue.esm-bundler';
import router from "./router";
import MainApp from './components/MainApp.vue';
import { createPinia } from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'

import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import { VDataTable } from 'vuetify/labs/VDataTable'
import Toaster from '@meforma/vue-toaster';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';


const app = createApp({});
const vuetify = createVuetify({
    theme: { defaultTheme: 'light' },
    components,
    directives,
})

const pinia = createPinia()
pinia.use(piniaPluginPersistedstate)


app.component('Mainapp', MainApp);
app.component('VDataTable', VDataTable)
app.use(router)
app.use(Toaster)
app.use(pinia)
app.use(VueSweetalert2)
app.use(vuetify).mount('#app')

