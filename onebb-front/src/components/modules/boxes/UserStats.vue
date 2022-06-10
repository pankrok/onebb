<template>
  <div class="list-complete-item box">
    <div class="box-header">
      <h3 class="text-center">{{ $t('Your statistics') }}</h3>
    </div>
    <Transition name="fade" mode="out-in">
    <div class="box-content d-flex column a-i-center j-c-center" v-if="userdata" :key="userdata">
        <div class="relative">
            <router-link  
                class="px-1"
                :to="{ name: 'Profile', params: {slug: $store.state.onebb.status.slug, id: $store.state.onebb.status.uid} }"
            >
                <img :src="$store.state.baseUrl + userdata.avatar" alt="Avatar" class="avatar img100">
            </router-link>
        </div>
        <ul class="list w-100">
            <li v-if="userdata.user_group" class="list-item text-center px-1">
                <span>{{ $t('username') }}:</span>
                <span v-html="parseUsername(userdata.username, userdata.user_group.html_code)"></span>
            </li>
         
            <li class="list-item text-center px-1"><span>{{ $t('Group') }}:</span> <span v-if="userdata.user_group" v-html="userdata.user_group.group_name"></span></li>
            <li class="list-item text-center px-1"><span>{{ $t('Posts') }}:</span> <span>{{ userdata.posts_no }}</span></li>
            <li class="list-item text-center px-1"><span>{{ $t('Plots') }}:</span> <span>{{ userdata.plots_no }}</span></li>
        </ul>
    </div>
    <div v-else class="box-content text-center" key="stranger">
        <strong>{{ $t('Hello stranger') }}</strong>
    </div> 
    </Transition>
  </div>
</template>

<script>
export default {
  name: 'UserStats',
  data() {
    return {
        user: null
    }
  },
  computed: {
    userdata() {
        if (this.$store.state.onebb.status.loggedIn === true) {
            if (this.user === null ) {
                this.getUsertada();
            }
            return this.user;
        } else {
            return false;
        }        
        
    }
  },
  methods: {
    getUsertada() {
        if (this.$store.state.onebb.status.uid === null) {
            return {}
        }
    
        this.$store.dispatch(
            'onebb/get', 
            {
                resource: 'user', 
                id: this.$store.state.onebb.status.uid
            }
        ).then(response => {
            this.user = response;
        });
    },
    
    parseUsername(username, groupe) {
        return groupe.replace('{{username}}', username);
    }
  }
  
}
</script>

