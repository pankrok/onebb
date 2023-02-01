<script setup lang="ts">
import Skeleton from '../components/skeletons/SkeletonPlot';
import { useStore } from 'vuex';
import { useI18n } from 'vue-i18n';

cont { t } = useI18n();

</script>

<template>
    <div class="f-grow" :key="$route.name">
        <div class="box">
            <div class="box-header">
            <Transition name="fade" mode="out-in">
               <span v-if="plot.name" :key="plot.name" class="d-flex j-c-space-between a-i-center"><h1>{{ plot.name }}</h1> <button v-if="$store.state.onebb.status.mcp" class="btn btn-warning" @click="plotMod = plot.id">EDIT PLOT</button></span>
               <h1 v-else :key="loading">{{ t('loading') }}</h1>
               
            </Transition>
            </div>
        </div>
        <div v-if="this.page > 1 || this.next" class="box row d-flex j-c-space-between p-1">
                <span><button v-if="this.page > 1" class="btn btn-secondary j-s-start" type="button" @click="prevPage">{{ t('Prev') }}</button></span>
                <span><button v-if="this.next" class="btn btn-secondary j-s-end" type="button" @click="nextPage">{{ t('Next') }}</button></span>
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
                        {{ t('Posts') }} - {{ post.user.posts_no }}
                    </li>
                    <li class="list-item">
                        {{ t('Plots') }} - {{ post.user.plots_no }}
                    </li>
                </ul>
               </div>
              
               </div>
                <div class="f-grow column j-c-space-between">
                <span>
                    <div class="box-content my-1 d-sm-none-xl-flex border-bottom-primary-1">{{ t('created at') }} {{ dateFormat(post.created_at) }}</div>
                    <div class="box-content my-1" v-html="post.content" :id="'post' + post.id"> </div>
                </span>
                <div v-if="$store.state.onebb.status.mcp" class="box-content my-1 a-s-end"><button class="btn btn-warning" @click="postMod = post.id"><i class="fas fa-edit "></i></button></div>
            </div>
            </div>
            </TransitionGroup>
        </div>
        </Transition>
         <div v-if="this.page > 1 || this.next" class="box row d-flex j-c-space-between p-1">
            <span><button v-if="this.page > 1" class="btn btn-secondary j-s-start" type="button" @click="prevPage">{{ t('Prev') }}</button></span>
            <span><button v-if="this.next" class="btn btn-secondary j-s-end" type="button" @click="nextPage">{{ t('Next') }}</button></span>
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
    <!-- 
    <PlotMod v-model:pid="plotMod" />
    <PostMod v-model:pid="postMod" />
    -->
</template>