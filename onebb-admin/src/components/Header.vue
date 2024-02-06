<template>
<Transition name="slide-top-fade">
  <div v-if="$store.state.onebb.status.acp" class="header col-12">
    <div class="header-wrapper">

      <ul class="second_menu">

        <div class="row">
        <!-- <Bell /> -->
        <li class="list-complete-item">
            <button @click="localeMenu = !localeMenu" id="theme-toggle" class="btn xs rounded">
                <i class="fas fa-flag"></i>
            </button>
            <Transition name="slide-top-fade">
                <div v-if="localeMenu" class="absolute top-3 z-1000 box mr-4">         
                    <ul class="list">
                        <li @click="setLocale('pl')" class="list-complete-item pointer">🇵🇱 PL</li>
                        <li @click="setLocale('en')" class="list-complete-item pointer">🇺🇸 EN</li>
                    </ul>
                </div>
            </Transition>
        </li>
        <li class="list-complete-item d-flex j-c-center a-i-center"><img :src="uri + $store.state.onebb.status.avatar" alt="Avatar" class="avatar xs mx-1"><!--<i class="fa-solid fa-caret-down"></i>--></li>
        <li @click="toggleMenu" id="menuToggle" :class="{ active: $store.state.mobileMenu }" class="d-sm-block">
            <span></span>
            <span></span>
            <span></span>
        </li>   
        </div>
      </ul>
    </div>
  </div>
</Transition>
</template>

<script>

// import Bell from './Bell'; 

export default {
  name: 'Header',
  components: {
    // Bell
  },
  data(){
    return {
        dmode: false,
        localeMenu: false,
        uri: document.getElementById('app').dataset.url + '/',
    }
  },
  methods: {
    darkMode() {
        this.dmode = !this.dmode;
        if (this.dmode == true) {
            document.documentElement.setAttribute('data-theme', 'dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.setAttribute('data-theme', 'light');
            localStorage.setItem('theme', 'light');
        }
    },
    toggleMenu() {
        this.$store.dispatch('toggleMenu');
    },
    setLocale(locale) {
        localStorage.setItem('locale', locale);
        this.$root.$i18n.locale = locale;   
        this.localeMenu = false;
    },
  }
}
</script>

