import { createApp } from 'vue'

import App from './App.vue'
import router from './router'
import store from './store'
import './registerServiceWorker'
import i18n from './i18n'
import plugins from './services/plugins.service';

plugins.setStore(store);
createApp(App).use(i18n).use(store).use(router).use(plugins).mount('#app')
