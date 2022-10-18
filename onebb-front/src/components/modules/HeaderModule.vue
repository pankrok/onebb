<template>

    <SignInModule v-model:show="signInModal" />

<nav>
  <div class="header">
    <div class="header-wrapper">
    <div class="logo">
        <router-link :to="{ name: 'Home' }">
            <img class="logo-img" :src="$store.state.baseUrl + 'skins/standard/img/logo.png'">
        </router-link>
    </div> 
 
    <TransitionGroup name="list-complete" tag="ul" class="second_menu" mode="out-in">
      <li v-if="loggedIn" class="list-complete-item">
        <button @click="msg()" id="theme-toggle" class="btn btn-secondary relative">
            <span v-if="$store.state.messenger.unread > 0" class="badge danger">{{ $store.state.messenger.unread }}</span>
            <i class="fa-regular fa-envelope"></i>
        </button>
      </li>
      <li class="list-complete-item">
        <button @click="$emit('darkMode', !darkmode)" id="theme-toggle" class="btn btn-secondary">
            <i :class="[darkmode ? true : 'fa-sun', 'fa-moon']" class="fas"></i>
        </button>
      </li>
      <li class="list-complete-item" v-if="!loggedIn"><a href="#" @click.stop.prevent="signInModal = true" class="px-1"><i class="fas fa-sign-in-alt fa-lg"></i> <span class="d-sm-none">{{ $t('sign in') }}</span></a></li>
      <li class="list-complete-item" v-if="!loggedIn">
        <router-link class="px-1" :to="{ name: 'SignUp' }"><i class="fas fa-user-plus fa-lg"></i> <span class="d-sm-none">{{ $t('sign up') }}</span></router-link>
      </li>
      <!-- logged menu -->
      <!-- create dropdown -->
      <li @click="navDropdown = !navDropdown" class="list-complete-item dropdown-nav" v-if="loggedIn">
         <img :src="$store.state.baseUrl + $store.state.onebb.status.avatar" alt="Avatar" class="avatar xs mx-1"><i class="fa-solid fa-caret-down"></i>
         <Transition name="slide-fade">
         <ul v-if="navDropdown" class="dropdown">
            <li class="dropdown-item" v-if="loggedIn">
                <router-link  
                    class="px-1"
                    :to="{ name: 'Profile', params: {slug: $store.state.onebb.status.slug, id: $store.state.onebb.status.uid} }"
                    ><i class="fa-solid fa-user"></i> <span>{{ $t('profile') }}</span>
                </router-link>
            </li>
            <li class="dropdown-item" v-if="loggedIn">
                <router-link  
                    class="px-1"
                    :to="{ name: 'UserConfig', params: {id: $store.state.onebb.status.uid} }"
                    ><i class="fa-solid fa-cog"></i> <span>{{ $t('configuration') }}</span>
                </router-link>
            </li>
            <li class="dropwodn-item header"></li>
            <li class="dropdown-item" v-if="loggedIn"><a href="#" class="px-1" @click.stop.prevent="logout"><i class="fa fa-sign-out fa-lg"></i> <span>{{ $t('logout') }}</span></a></li>
         </ul>
         </Transition>
      </li>
      
      <li @click="toggleMenu = !toggleMenu" id="menuToggle" :class="{ active: toggleMenu }" class="d-sm-block">
            <span></span>
            <span></span>
            <span></span>
       </li>   
     </TransitionGroup> 
    <!-- end logged menu -->
    </div>
  </div>
  <div id="menu" :class="{ show: toggleMenu }" class="header second">
    <div class="header-wrapper">
    <ul class="menu">
      <li><router-link :to="{ name: 'Home' }">Home</router-link></li>
      <li v-for="(menuItem, index) in menuItems" :key="index">
        <router-link :to="{ name: 'Page', params: {slug: menuItem.slug, id: menuItem.id} }">
            <span v-html="menuItem.name"></span>
        </router-link>  
      </li>
    </ul>
    </div>
  </div>
  </nav>
  </template>

<script>
// FIXME - that should be in store!
 
        
// END FIXME
import SignInModule from './auth/SignInModule';
export default {
  name: 'HeaderModule',
  props: {
    darkmode: Boolean,
  },
  components: {
    SignInModule
  },
  data() {
    return {
        signInModal: false,
        toggleMenu: false,
        navDropdown: false,
        menuItems: {}
    }
  },
  computed: {
    loggedIn() {
      return this.$store.state.onebb.status.loggedIn;
    }
  },
  methods: {
    logout() {
        this.$store.dispatch('onebb/logout');
    },
    msg() {
        this.$store.dispatch('onebb/toggleMsg');
        this.$store.dispatch('onebb/showMsgBox');
    },

  },
   mounted() {
    this.$store.dispatch('onebb/get', { resource: 'page' }).then(response => {
        this.menuItems = response['hydra:member'];
    });
    },
    
  emits: ['darkMode']
  
}
</script>
