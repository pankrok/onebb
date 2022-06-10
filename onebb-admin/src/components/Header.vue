<template>
<Transition name="slide-top-fade">
  <div v-if="$store.state.onebb.status.acp" class="header col-12">
    <div class="header-wrapper">
    
      <li @click="toggleMenu" id="menuToggle" :class="{ active: $store.state.mobileMenu }" class="d-sm-block">
            <span></span>
            <span></span>
            <span></span>
       </li>   
      <ul class="second_menu">
        <li class="list-complete-item">
            <button id="bells" class="btn btn-secondary xs rounded">
                <i class="fa-solid fa-bell"></i>
                <span class="badge-wrapper">
                    <span class="badge danger">1</span>
                </span>
            </button>
        </li>
        <li class="list-complete-item"><button @click="darkMode" id="theme-toggle" class="btn btn-secondary xs rounded"><i class="fa-moon fas"></i></button></li>
        <li class="list-complete-item"><img :src="uri + $store.state.onebb.status.avatar" alt="Avatar" class="avatar xs mx-1"><!--<i class="fa-solid fa-caret-down"></i>--></li>
      </ul>
    </div>
  </div>
</Transition>
</template>

<script>


export default {
  name: 'Header',
  components: {
  },
  data(){
    return {
        dmode: false,
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
    }
  },
  
}
</script>

