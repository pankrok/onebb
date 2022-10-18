const flags = {
    plugins: true, 
    messenger: true,
    chat: false,
};

import { createApp } from 'vue'

import App from './App.vue'
import router from './router'
import store from './store'
import './registerServiceWorker'
import i18n from './i18n'
import plugins from './services/plugins.service';

let app = createApp(App).use(i18n).use(store).use(router);

if(flags.plugins === true) {
    plugins.setStore(store);
    window.$obbPlugins = plugins;
    app.use(plugins);
} 

app.mount('#app');
