<template>
<Transition name="fade" mode="out-in">
<div v-if="loading" :key="skeleton">
<Skeleton :boxes="boxes" />
</div>
<div v-else :key="$route.name">
<div v-for="category in categories" :key="category.id" class="box">
      <div class="box-header">
        <h2><router-link :to="{ name: 'Category', params: { slug: category.slug, id: category.id }}">{{ category.name }} <span v-if="$store.state.onebb.status.acp && !category.active"><i class="fa-solid fa-eye-slash"></i></span></router-link></h2>
      </div>
      <div class="box-content">
      <ul class="list">
        <li v-for="board in category.boards" :key="board.id" class="list-item">
          <div class="content f-grow">
          <h3>
            <router-link :to="{ name: 'Board', params: { slug: board.slug, id: board.id }}">{{ board.name }}</router-link>
          </h3>
          <p v-if="board.description" v-html="board.description"></p>
          </div>
          <div class="row col-2 text-right d-sm-none">
            <strong>{{ board.plots_no }} <i class="fas fa-comments"></i> / {{ board.posts_no }} <i class="fas fa-comment-dots"></i></strong>
          </div>
          <div class="autor col-sm-3" v-if="board.last_active_user">
            <div class="px-xl-1 m-0">
              <div class="d-sm-none" v-html="parseUsername(board.last_active_user.username, board.last_active_user.user_group.html_code)"></div>
              <div class="text-center">{{ dateFormat(board.updated_at) }}</div>
            </div>
           <img :src="$store.state.baseUrl + board.last_active_user.avatar" alt="Avatar" class="avatar">
          </div>
          <div v-else class="autor col-sm-3">
            <div class="px-xl-1 m-0">
                {{ $t('board is empty') }}
            </div>
          </div>
         </li>
      </ul>
      </div>
    </div> 
 </div> 
 </Transition>
</template>
<script>
import Skeleton from './skeletons/SkeletonCategorie';
import moment from 'moment';


export default {
  name: 'CategoryComponent',
  props: {
    categories: Object,
    loading: Boolean,
    boxes: Number,
  },
  methods: {
    parseUsername(username, groupe) {
        return groupe.replace('{{username}}', username);
    },
    dateFormat(value) {
        return moment(String(value)).format('YYYY-MM-DD');
    },
  },
  components: {
    Skeleton
  }
}
</script>