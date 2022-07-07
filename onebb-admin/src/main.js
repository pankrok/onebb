import { createApp } from 'vue'
import App from './App.vue'
import './registerServiceWorker'
import router from './router'
import store from './store'
import i18n from './i18n'
import plugins from './services/plugins.service';

plugins.setStore(store);
plugins.setRouter(router);

createApp(App).use(i18n).use(store).use(router).use(plugins).mount('#app')
