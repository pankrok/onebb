<template>
<Transition name="fade" mode="out-in">
<div v-if="loading" :key="skeleton">
<Skeleton :boxes="boxes" /> 
</div>
<div v-else :key="$route.name">

    <div class="box">
      <div class="box-header">
        <h2>{{ board.name }}</h2>
      </div>
    </div>
    <div v-if="$store.state.onebb.status.loggedIn" class="row mx-1">
        <router-link :to="{ name: 'NewPlot', params: { id: board.id }}" class="btn btn-secondary"><i class="fas fa-plus"></i> {{ $t('new plot') }}</router-link>
    </div>
    <div class="box my-1">
        <ul class="box-content list">
        <li v-for="plot in board.plots" class="list-item-no-border" :key="plot.id">
            <div class="mr-4"><i class="text-success fas fa-file fa-2x"></i></div>
          <div class="content f-grow"><h3>
            <router-link :to="{ name: 'Plot', params: { slug: plot.slug, id: plot.id, page:(Math.ceil(plot.post_no/20)) }}">{{ plot.name }}</router-link>
          </h3>
          <span>przez: <strong style="color:red"><i class="fas fa-circle-notch fa-spin"></i> {{ plot.user.username }}</strong></span>
          </div>
          <div class="row col-2 text-center d-sm-none">
            <strong>{{ plot.post_no }} <i class="fas fa-comments"></i> /  {{ plot.views }} <i class="fas fa-eye"></i></strong>
          </div>
          <div class="autor col-sm-4">
            <div class="column px-xl-1 m-0">
              <div class="d-sm-none text-right" v-html="parseUsername(plot.last_active_user.username, plot.last_active_user.user_group.html_code)"></div>
              <div class="text-center"><i class="far fa-clock"></i>  {{ dateFormat(plot.updated_at) }}</div>
            </div>
           <img :src="$store.state.baseUrl + plot.last_active_user.avatar" alt="Avatar" class="avatar"></div>
         </li>
         </ul>
         
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
    board: Object,
    loading: Boolean,
    boxes: Number,
  },
  methods: {
    parseUsername(username, groupe) {
        return groupe.replace('{{username}}', username);
    },
    dateFormat(value) {
        return moment(String(value)).format('YYYY-MM-DD');
    }
  },
  components: {
    Skeleton
  }
}
</script>