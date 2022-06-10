<template>
<Transition name="slide-left-fade">
<div class="column left-menu" :class="{ show: $store.state.mobileMenu }" v-if="$store.state.onebb.status.acp">
    <div class="logo row a-i-center j-c-saround-xl px-1">
        <a class="d-flex a-i-center j-c-center" href="/">
            <img class="logo-img" src="../assets/logo.png"> 
            <span class="mx-1 fs-24">OneBB</span>
        </a>
    </div>

    <div class="f-grow">
    <ul class="list" @click="hideMenu">
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
        <li class="list-item">
            <router-link :to="{ name: 'EmailList' }"><i class="far fa-file-alt "></i> {{ $t('email templates') }}</router-link>
        </li>
        <li class="list-item">
            <router-link :to="{ name: 'SkinList' }"><i class="fas fa-paint-brush"></i> {{ $t('skins') }}</router-link>
        </li>
        <li class="list-item">
            <router-link :to="{ name: 'Configuration' }"><i class="fa-solid fa-gears"></i> {{ $t('configuration') }}</router-link>
        </li>
        <hr />
        <li class="list-item" v-if="$store.state.onebb.status.loggedIn">
            <a href="#" class="px-1" @click="logout"><i class="fa fa-sign-out fa-lg"></i> {{ $t('logout') }}</a>
        </li>
    </ul>
    </div>
</div>
    </Transition>
</template>

<script>


export default {
  name: 'Navigation',
  methods: {
  hideMenu() {
    this.$store.dispatch('hideMenu');
  },
   logout() {
        this.$store.dispatch('onebb/logout');
        this.$router.push({ name: 'Login'} );
    },
  }
}
</script>

