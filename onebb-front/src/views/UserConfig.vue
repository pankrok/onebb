<template>
<Transition name="fade" mode="out-in">
    <div v-if="user" class="f-grow row" :key="$route.name">
        <div class="col-3">
            <div class="box">
            <div class="box-content d-flex column a-i-center j-c-center">
                <div class="relative"> 
                    <img :src="$store.state.baseUrl + user.avatar" alt="Avatar" class="avatar img100">
                </div>
                <ul class="list w-100">
                    <li class="list-item text-center px-1">
                        <span>{{ $t('username') }}:</span>
                        <span v-html="parseUsername(user.username, user.user_group.html_code)"></span>
                    </li>
                 
                    <li class="list-item text-center px-1"><span>{{ $t('Group') }}:</span> <span  v-html="user.user_group.group_name"></span></li>
                    <li class="list-item text-center px-1"><span>{{ $t('Posts') }}:</span> <span>{{ user.posts_no }}</span></li>
                    <li class="list-item text-center px-1"><span>{{ $t('Plots') }}:</span> <span>{{ user.plots_no }}</span></li>
                </ul>
            </div>
           
            </div>
        </div>
        <div class="col-9" v-if="canEdit">
            <div class="box">
                <div class="box-content row">
                    <div class="col-12 column">
                      <label for="password">{{ $t('password') }}</label>
                      <input v-model="password" type="password" name="password" id="password" class="form-control">
                    </div>
                    <div class="col-12 column">
                      <label for="vpassword">{{ $t('validate password') }}</label>
                      <input v-model="vpassword" type="password" name="password" id="vpassword" class="form-control">
                    </div>
                    <div class="col column my-1">
                      <button  @click="changePassword" type="submit" class="btn btn-secondary">{{ $t('change password') }}</button>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <div v-else class="f-grow row" :key="skeleton">
        <div class="col-12">
            <Skeleton :boxes="1" /> 
        </div>
    </div>
</Transition>
</template>



<script>
import Skeleton from '../components/skeletons/SkeletonPlot';

export default {
  name: 'Profile',
  data() {
    return {
        user: null,
        loading: true,
        password: null,
        vpassword: null,
    }
  },
  computed: {
    canEdit() {
        return (
            this.$store.state.onebb.status.uid === this.user.id
        )
    }
  },
  methods: {
    parseUsername(username, groupe) {
        return groupe.replace('{{username}}', username);
    },
    
    changePassword() {
        if (this.password === this.vpassword) {
        this.loading = true;
        this.$store.dispatch('onebb/put', { 
            resource: 'user', 
            id: this.$route.params.id, 
            data: {
                password: this.password
            }
                
            }).then(response => {
                console.log(response);
                this.loading = false;
            });    
        }
    }
    
  },
  mounted() {
    this.$store.dispatch('onebb/get', { resource: 'user', id: this.$route.params.id}).then(response => {
        this.user = response;
        document.querySelector('meta[name="description"]').setAttribute("content", this.$store.state.defaultMeta + '-' + this.$t('user configuration'));
        this.loading = false;
        this.$store.dispatch('plugins/reloadPlugins');
        document.title = this.$store.state.defaultTitle + '-' + this.$t('user configuration');
    });    
  },
  components: {
    Skeleton
  }
}
</script>
    