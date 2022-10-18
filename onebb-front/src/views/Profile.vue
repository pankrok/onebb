<template>
<Transition name="fade" mode="out-in">
    <div v-if="user" class="f-grow row" :key="$route.name">
        <div class="col-3">
            <div class="box">
            <div class="box-content d-flex column a-i-center j-c-center">
                <div class="relative"> 
                    <img :src="$store.state.baseUrl + user.avatar" alt="Avatar" class="avatar img100">
                    <span v-if="canEdit" class="uploadico" @click="showCropper = !showCropper"><i class="fa-solid fa-image fa-2xl" ></i></span>
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
        <div class="col-9">
            <div class="box" v-for="post in posts" :key="post.id">
                <div class="box-content">
                    <span v-html="post.content"></span>
                </div>
            </div>
        </div>
        <Cropper v-model:show="showCropper" />
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
import Cropper from '../components/modules/Cropper';

export default {
  name: 'Profile',
  data() {
    return {
        user: null,
        loading: true,
        showCropper: false,
        posts: null,
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
    }
    
  },
  mounted() {
    this.$store.dispatch('onebb/get', { resource: 'user', id: this.$route.params.id}).then(response => {
        this.user = response;
        document.querySelector('meta[name="description"]').setAttribute("content", this.$store.state.defaultMeta + ' - ' + response.username);
        document.title = this.$store.state.defaultTitle + ' - ' + response.username;
        this.loading = false;
        this.$store.dispatch('plugins/reloadPlugins');
    });    
    this.$store.dispatch('onebb/get', { resource: 'user', subresource: 'posts?page=1&limit=20&order[created_at]=desc',  id: this.$route.params.id}).then(response => {
        this.posts = response['hydra:member'];
    }); 
  },
  components: {
    Skeleton,
    Cropper
  }
}
</script>
    