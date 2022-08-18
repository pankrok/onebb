<template>
    <div class="f-grow" :key="$route.name">
        <div class="box">
            <div class="box-header">
            <Transition name="fade" mode="out-in">
               <span v-if="plot.name" :key="plot.name" class="d-flex j-c-space-between a-i-center"><h1>{{ plot.name }}</h1> <button v-if="$store.state.onebb.status.mcp" class="btn btn-warning" @click="plotMod = plot.id">EDIT PLOT</button></span>
               <h1 v-else :key="loading">{{ $t('loading') }}</h1>
               
            </Transition>
            </div>
        </div>
        <div v-if="this.page > 1 || this.next" class="box row d-flex j-c-space-between p-1">
                <span><button v-if="this.page > 1" class="btn btn-secondary j-s-start" type="button" @click="prevPage">{{ $t('Prev') }}</button></span>
                <span><button v-if="this.next" class="btn btn-secondary j-s-end" type="button" @click="nextPage">{{ $t('Next') }}</button></span>
            </div>
        <Transition name="fade" mode="out-in">
        <div v-if="loading" :key="plotLoader">
            <Skeleton :boxes="10"/>
        </div>
        
        <div v-else :key="$route.name">
            
        <TransitionGroup name="list-complete" tag="div"  mode="out-in">
            <div v-for="post in posts" class="box my-1 row list-complete-item" :key="post.id">
            <div class="col-2">
               <div class="autor_post column-xl">
                <ul class="list-post">
                    <li class="list-item-no-border text-center-xl fd-sm-column-xl-row a-item-sm-start-xl-center">
                        <p class="my-1">
                        <router-link  
                            :to="{ name: 'Profile', params: {slug: post.user.slug, id: post.user.id} }"
                            v-html="parseUsername(post.user.username, post.user.user_group.html_code)"
                        >                       
                        </router-link> 
                        </p>
                        <p class="d-sm-block my-1">{{ dateFormat(post.created_at) }}</p>
                    </li>
                    <li class="list-item-no-border">
                        <router-link  
                            :to="{ name: 'Profile', params: {slug: post.user.slug, id: post.user.id} }"
                        >                        
                            <img :src="$store.state.baseUrl + post.user.avatar" alt="Avatar" class="avatar post-img">
                        </router-link>
                    </li>
                 </ul>
                 <ul class="list-post d-sm-none-xl-flex">
                    <li class="list-item"
                        v-html="post.user.user_group.group_name"
                    >
                    </li>
                    <li class="list-item">
                        {{ $t('posts') }} - {{ post.user.posts_no }}
                    </li>
                    <li class="list-item">
                        {{ $t('plots') }} - {{ post.user.plots_no }}
                    </li>
                </ul>
               </div>
              
               </div>
                <div class="f-grow column j-c-space-between">
                <span>
                    <div class="box-content my-1 d-sm-none-xl-flex border-bottom-primary-1">{{ $t('created at') }} {{ dateFormat(post.created_at) }}</div>
                    <div class="box-content my-1" v-html="post.content" :id="'post' + post.id"> </div>
                </span>
                <div v-if="$store.state.onebb.status.mcp" class="box-content my-1 a-s-end"><button class="btn btn-warning" @click="postMod = post.id"><i class="fas fa-edit "></i></button></div>
            </div>
            </div>
            </TransitionGroup>
        </div>
        </Transition>
         <div v-if="this.page > 1 || this.next" class="box row d-flex j-c-space-between p-1">
            <span><button v-if="this.page > 1" class="btn btn-secondary j-s-start" type="button" @click="prevPage">{{ $t('Prev') }}</button></span>
            <span><button v-if="this.next" class="btn btn-secondary j-s-end" type="button" @click="nextPage">{{ $t('Next') }}</button></span>
        </div>
        <div v-if="$store.state.onebb.status.loggedIn"  class="box">
        <Transition name="slide-fade" mode="out-in">
            <div class="alert info j-c-center a-i-center" v-if="alert" :key="alert" style="position: absolute; z-index: 1000; left: 40%; bottom: 5%;">
                    <strong>{{ alert }}</strong>
            </div>
        </Transition>
        <Transition name="slide-fade" mode="out-in">
            <div v-if="!editor && $store.state.onebb.status.loggedIn"  class="box-content d-flex j-c-end" :key="jodit-btn">
                <button  @click="editor = true" class="btn btn-secondary">Odpowiedz</button>
            </div>
             <div v-else-if="editor && $store.state.onebb.status.loggedIn" class="box-content column" :key="jodit">
                <Jodit v-model:value="content" :config="jodit_cfg" />
                <button class="btn btn-secondary my-1 a-s-end" @click="sendPost">Prześlij odpowiedź</button>
            </div>
        </Transition>
        </div>  
    </div>
    <PlotMod v-model:pid="plotMod" />
    <PostMod v-model:pid="postMod" />
</template>



<script>

import { defineAsyncComponent } from 'vue'
import Skeleton from '../components/skeletons/SkeletonPlot';
import moment from 'moment';

export default {
  name: 'Plot',
  data() {
    return {
        plot: {},
        page: 1,
        next: null,
        limit: null,
        posts: {},
        content: '',
        alert: null,
        loading: true,
        editor: false, 
        jodit_cfg: {},
        plotMod: 0,
        postMod: 0,
    }
  },
  mounted() {
    this.page = (this.$route.params.page ?? 1);
    this.limit = (this.$route.params.limit ?? null);
  
  
    this.$store.dispatch('onebb/get', {
        resource:'plot',
        id: this.$route.params.id
    }).then(response => {
        this.plot = response;
    });
    this.getPosts(
        this.page,
        this.limit
    );
    
  },
  methods: {
    sendPost: function() {

        if (this.content === '') {
        
            this.alert = 'content cannot be empty!';
            setTimeout(() => {
                this.alert = null;
            }, 5000);
            return null;
        }
        
        this.$store.dispatch('onebb/post', {
            resource: 'replay',
            data: {
                plot: "/api/plots/" + this.$route.params.id,
                content: this.content,
            }
        }).then(response => {
        console.log(response);
            this.posts.push(response);
            this.content = '';
        });
    },
    
    nextPage() {
        if (this.next === false)
            return;
    
        this.page++;
        this.$router.push({ name: 'Plot', params: { 
            slug: this.$route.params.slug, 
            id: this.$route.params.id, 
            limit: this.$route.params.limit, 
            page: this.page 
        }});  
        this.getPosts();
        
    },
    
    prevPage() {
        if (this.page === 1 )
            return;
    
        this.page--;
        this.$router.push({ name: 'Plot', params: { 
            slug: this.$route.params.slug, 
            id: this.$route.params.id, 
            limit: this.$route.params.limit, 
            page: this.page 
        }});  
        this.getPosts();
    },
    
    getPosts() {
        this.loading = true;
        this.$store.dispatch('onebb/get', {
            resource:'plot', 
            subresource: 'posts',
            page: this.page,
            limit: this.limit,
            id: this.$route.params.id
        }).then(response => {
            this.posts = response['hydra:member'];
            this.next = response['hydra:view']['hydra:next'] ?? false;
            this.loading = false;
            this.$store.dispatch('plugins/reloadPlugins');
        })
    },
    
    dateFormat(value) {
        return moment(String(value)).format('YYYY-MM-DD hh:mm:ss');
    },
    parseUsername(username, groupe) {
        return groupe.replace('{{username}}', username);
    }
  },
  components: {
    Jodit: defineAsyncComponent(() =>
        import('../components/modules/JoditEditor')
    ),
    PlotMod: defineAsyncComponent(() =>
        import('../components/modules/moderator/PlotMod')
    ),
    PostMod: defineAsyncComponent(() =>
        import('../components/modules/moderator/PostMod')
    ),
    Skeleton
  }
}
</script>

<styles scoped>

.jodit-toolbar__box:not(:empty) {
  background-color: var(--primary) !important;
  border-bottom: 1px solid var(--background) !important;
}

.jodit-ui-separator {
  border-left: 0;
  border-right: 1px solid var(--background) !important;
}


</styles>
