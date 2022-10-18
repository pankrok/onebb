<template>

  <HeaderModule @dark-mode="darkMode" v-model:darkmode="dmode" />
  <main>
  
  <TransitionGroup name="list-complete" tag="div" class="container" mode="out-in"> 
  <BreadcrumbsModule class="list-complete-item" />
    <div class="col-12 list-complete-item" v-if="boxes.top">
        
        <div v-for="box in boxes.top" :key="box.name">
            <component :is="box.engine" :name="box.name" :content="box.html" />
        </div>
       
    </div>
    
    <div class="col-3 list-complete-item" v-if="boxes.left">
        
        <div v-for="box in boxes.left" :key="box.name">
            <component :is="box.engine" :name="box.name" :content="box.html" />
        </div>
       
    </div>

    <div class="f-grow list-complete-item p-relative" :key="main">
        <router-view v-slot="{ Component }" :key="$route.fullPath">

            <component :is="Component" />
    
        </router-view>
    </div>
    
    <div class="col-3 list-complete-item" v-if="boxes.right">
   
        <div v-for="box in boxes.right" :key="box.name">
            <component :is="box.engine" :name="box.name" :content="box.html" />
        </div>
    
    </div>
    
    <div class="col-12 list-complete-item" v-if="boxes.bottom">
        
        <div v-for="box in boxes.bottom" :key="box.name">
            <component :is="box.engine" :name="box.name" :content="box.html" />
        </div>
       
    </div>
    
  </TransitionGroup>
  <Transition name="fade">
    <Messenger v-if="logged" />
  </Transition>
  </main>
  <FooterModule />
  
</template>

<script>

import HeaderModule from './components/modules/HeaderModule';
import BreadcrumbsModule from './components/modules/BreadcrumbsModule';
import FooterModule from './components/modules/FooterModule';
import Messenger from './components/modules/messenger/Messenger';

// box modules import
import CustomBox from './components/modules/boxes/CustomBox';
import PluginBox from './components/modules/boxes/PluginBox';
import UserStats from './components/modules/boxes/UserStats';
// import ChatBox from './components/modules/ChatBox'; // NOT IMPLEMENTED YET!

import moment from 'moment';

export default {
  name: 'App',
  components: {
    HeaderModule,
    BreadcrumbsModule,
    FooterModule,
    CustomBox,
    PluginBox,
    UserStats,
    Messenger,
  },
  data(){
    return {
        msg: String,
        block: true,
        dmode: false,
        boxReady: false
    }
  },
  computed: {
    logged() {
        return (this.$store.state.onebb.status.loggedIn && this.$store.state.onebb.messenger.show); 
    },
    boxes() {
        if(typeof(window.$obbPlugins) == "object") {
            window.$obbPlugins.context(this.$route.name);
        }
        
        if(this.boxReady) {
            return this.$store.state.boxes[this.$route.name];
        }
        
        return {
            top: null,
            bottom: null,
            left: null,
            right: null,  
        };
    }
  },
  methods: {
    
    darkMode(foo) {
        this.dmode = foo;
        if (this.dmode == true) {
            document.documentElement.setAttribute('data-theme', 'dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.setAttribute('data-theme', 'light');
            localStorage.setItem('theme', 'light');
        }
    },
    msgPooling() {
        let subres = '?page1&limit20';
        if (this.$store.state.messenger.lastCheck !== null) {
            subres += '&updated_at%5Bstrictly_after%5D=' + moment(this.$store.state.messenger.lastCheck).format('YYYY-MM-DD HH:mm:ss')
        }
    
        this.$store.dispatch('onebb/get', {
            resource: 'messenger', 
            subresource: subres
        }).then(r => {
            let last = this.$store.state.messenger.lastCheck;
            if (r.code === 401) {
                this.$store.dispatch('messenger/setShortPooling');
                return null;
            }
            
            if(r['hydra:member'].length > 0) {
                last = r['hydra:member'][0].updated_at; 
                this.$store.dispatch('messenger/setCache', r['hydra:member']);
                
                r['hydra:member'].forEach(el =>{
                    if (typeof(this.$store.state.messenger.readMessages[el.id]) !== 'undefined') {
                        if (this.$store.state.messenger.readMessages[el.id]  < el.updated_at) {
                            this.$store.dispatch('messenger/incrementUnread');
                        }
                    } else {
                        this.$store.dispatch('messenger/incrementUnread');
                        this.$store.dispatch('messenger/setReadMessage', el);
                    }
                });
            }
            
            this.$store.dispatch('messenger/setLastCheck', last);
        });
    }
  },
  mounted() {
    
    if(localStorage.getItem('user') == 'true') {
        this.$store.dispatch('onebb/refresh', {
    });
    }
    let storedTheme = localStorage.getItem('theme');
    if (storedTheme == 'dark') {
        document.documentElement.setAttribute('data-theme', storedTheme);
        this.dmode = true;
    } else {
        document.documentElement.setAttribute('data-theme', 'light');
        this.dmode = false;
    }
    
    this.$store.dispatch('onebb/get', {resource:'skin', subresource: '?active=1'}).then(response => {
        this.$store.dispatch('boxes/init', response['hydra:member'][0].skinBoxes);
        this.boxReady = true;
    })
    
    let msgPooling = () => {
        if (this.$store.state.onebb.status.loggedIn) {
            this.msgPooling();
        }
        setTimeout(msgPooling, this.$store.state.messenger.pooling);
    }
    msgPooling();
    
  }
}
</script>

<style>

@import './assets/transitions.css';
@import './assets/styles.css';
@import 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css';

</style>
