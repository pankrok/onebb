<template>

<main>
<div class="container row">
    <Navigation />
    <div class="f-grow">
        <div class="row">
          <Header />
          <Transition name="fade">
          <div v-if="$store.state.loading" class="col-12 relative">
                    <span class="box-loader"></span>
            </div>
            </Transition>

            <div class="view">
            <Transition name="fade" mode="out-in">
                <span v-if="$store.state.loading" class="loading_cover"></span>
            </Transition>
                <router-view v-slot="{ Component }">
                  <Transition name="slide-fade" mode="out-in">     
                    <component :is="Component" class="col-12"/>
                  </Transition>
                </router-view>
            </div>  
        </div>
  </div>
  </div>
</main>
</template>

<script>

import  './assets/modal.css';
import  './assets/transitions.css';
import  './assets/styles.css';

import Navigation from './components/Navigation'; 
import Header from './components/Header'; 

export default {
  name: 'App',
  components: {
    Navigation,
    Header,
  },
  data(){
    return {
        
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
    logout() {
        this.$store.dispatch('onebb/logout');
        this.$router.push({ name: 'Login'} );
    },
  },
  mounted() {

    this.$store.dispatch('loaded');
      
    let storedTheme = localStorage.getItem('theme');
    if (storedTheme == 'dark') {
        document.documentElement.setAttribute('data-theme', storedTheme);
        this.dmode = true;
    } else {
        document.documentElement.setAttribute('data-theme', 'light');
        this.dmode = false;
    }    
  },
}
</script>

<style>

@import 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css';

</style>
