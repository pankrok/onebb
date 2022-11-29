<template>
<Transition name="slide-left-fade">
<div class="left-menu" :class="{ show: $store.state.mobileMenu }" v-if="$store.state.onebb.status.acp">
    <div class="logo row a-i-center j-c-saround-xl px-1">
        <a class="d-flex a-i-center j-c-center" href="/">
            <img class="logo-img" src="../assets/logo.png" alt="OneBB"> 
        </a>
    </div>

    <div class="main-menu">
    <TransitionGroup name="group-fade" tag="ul"  @click="hideMenu"  class="list">
        <li class="list-item">
            <router-link :to="{ name: 'Home' }"><i class="fas fa-tachometer-alt mr-3"></i> {{ $t('dashboard') }}</router-link>
        </li>
        <li class="list-item">
            <router-link :to="{ name: 'ForumList' }"><i class="fa-solid fa-table-list"></i> {{ $t('manage boards') }}</router-link>
        </li>
        <li class="list-item">
            <router-link :to="{ name: 'UsersList' }"><i class="fa-solid fa-users"></i> {{ $t('users') }}</router-link>
        </li>
        <li class="list-item">
            <router-link :to="{ name: 'GroupList' }"><i class="fa-solid fa-user-group"></i> {{ $t('groups') }}</router-link>
        </li>
        <li class="list-item">
            <router-link :to="{ name: 'PagesList' }"><i class="fas fa-file-alt"></i> {{ $t('pages') }}</router-link>
        </li>
       
        <li class="list-item" v-on:click.stop>
            <span 
                class="col-12 pointer active" 
                @click="showSkins = !showSkins"
            >
                <i class="fa-solid fa-image"></i> {{ $t('appearance') }}
                <i class="ml-2 fa-solid fa-angle-right rotate-anim" :class="{'fa-rotate-90': showSkins}"></i>
            </span>

            <Transition name="slide-left-fade">   
                <ul v-if="showSkins" name="list"  @click="hideMenu"  class="second-list">
                    <li class="list-item">
                        <router-link :to="{ name: 'SkinList' }"><i class="fas fa-paint-brush"></i> {{ $t('themes') }}</router-link>
                    </li>
                    <!-- <li class="list-item"> -->
                        <!-- <router-link :to="{ name: 'SkinList' }"><i class="fa-solid fa-cloud-arrow-down"></i> {{ $t('add theme') }}</router-link> -->
                    <!-- </li> -->
                </ul>
            </Transition>   

        </li>
        
        
       
       
        <li class="list-item" v-on:click.stop>
            <span 
                class="col-12 pointer active" 
                @click="showPlugins = !showPlugins"
            >
                <i class="fa-solid fa-plug"></i> {{ $t('plugins') }} 
                <i class="ml-2 fa-solid fa-angle-right rotate-anim" :class="{'fa-rotate-90': showPlugins}"></i>
            </span>
            
                <Transition name="slide-left-fade">  
                <ul v-if="showPlugins" name="list"  @click="hideMenu"  class="second-list">
                     <li class="list-item">
                        <router-link :to="{ name: 'PluginStore' }"><i class="fa-solid fa-plug-circle-bolt"></i> {{ $t('add plugin') }}</router-link>
                    </li>
                    <li class="list-item">
                        <router-link :to="{ name: 'Plugins' }"><i class="fa-solid fa-plug-circle-check"></i> {{ $t('installed plugins') }}</router-link>
                    </li>
                    
                    <li v-if="pluginsLoading"  class="list-item">
                        <i class="fas fa-circle-notch fa-spin"></i>
                     </li>
                    
                    <template v-for="plugin in $store.state.obbPlugins" :key="plugin.id">   
                      <li class="list-item" v-if="plugin.install" :key="plugin.name">
                         <router-link  v-if="plugin.install && plugin.acp"  :to="{ name: 'PluginControl',  params: {plugin: plugin.name}}" ><i class="fa-solid" :class="plugin.ico ? plugin.ico : 'fa-cog'"></i> {{ plugin.name }}</router-link>
                      </li> 
                    </template> 
                </ul>
                </Transition>
            
        </li>
        
        <li class="list-item" v-on:click.stop>
            <span 
                class="col-12 pointer active" 
                @click="showCfg = !showCfg"
            >
                <i class="fa-solid fa-gear"></i> {{ $t('settings') }}
                <i class="ml-2 fa-solid fa-angle-right rotate-anim" :class="{'fa-rotate-90': showCfg}"></i>
            </span>
                <Transition name="slide-left-fade">  
                <ul v-if="showCfg" name="list"  @click="hideMenu"  class="second-list">
                    <li class="list-item">
                        <router-link :to="{ name: 'EmailList' }"><i class="far fa-file-alt "></i> {{ $t('email templates') }}</router-link>
                    </li>
                    <li class="list-item">
                        <router-link :to="{ name: 'Configuration' }"><i class="fa-solid fa-gears"></i> {{ $t('configuration') }}</router-link>
                    </li>
                    <li class="list-item">
                        <router-link :to="{ name: 'Update' }">
                            <i class="fa-solid fa-code-compare"></i> 
                            {{ $t('update') }}
                        </router-link>
                    </li>
                </ul>  
                </Transition>
        </li>
        <hr />
        <li class="list-item" v-if="$store.state.onebb.status.loggedIn">
            <a href="#" class="px-1" @click="logout"><i class="fa fa-sign-out fa-lg"></i> {{ $t('logout') }}</a>
        </li>
    </TransitionGroup>
    </div>
</div>
    </Transition>
</template>

<script>


export default {
  name: 'Navigation',
  data() {
    return {
        pluginsLoading: true,
        showPlugins: false,
        showSkins: false,
        showCfg: false,
    }
  },
  watch: {
      '$store.state.onebb.status.acp': function() {
        if (this.$store.state.onebb.status.acp === true) {
            this.loadPlugins();
        }
      }
    },
  methods: {
    hideMenu() {
      this.$store.dispatch('hideMenu');
    },
   logout() {
        this.$store.dispatch('onebb/logout');
        this.$router.push({ name: 'Login'} );
    },
    loadPlugins() {
        this.$store.dispatch('onebb/get', {resource:'plugins'}).then(response => {
            response['hydra:member'].forEach((el) => {
                this.$store.dispatch('obbPlugins/set', {plugin: el.name, data: el});
            });         
            this.pluginsLoading = false; 
    });
    }
  },
  
}
</script>