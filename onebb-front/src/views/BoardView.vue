<script setup lang="ts">
import { useRoute, useRouter, RouteRecordName } from "vue-router";
import { useStore } from "vuex";
import { ref, watch, computed } from "vue";
import BoardComponent from "@/components/ui/partial/Board.vue";
import { IBoard, IPlot } from "@/interfaces/obbApiInterface";
import { BOARD, PLOT } from "@/services/api/obbResources";
import api from "@/services/api/api";

const store = useStore();
const route = useRoute();
const router = useRouter();
const routeName = ref<RouteRecordName>("");

const next = () => {
  const p =  route.params.page ?  Number(route.params.page) + 1 : 2;
  router.push({ name: 'Board', params: { slug: route.params.slug, id: route.params.id, page: p} })
}

const prev = () => {
  const p =  route.params.page ?  Number(route.params.page) - 1 : 2;
  router.push({ name: 'Board', params: { slug: route.params.slug, id: route.params.id, page: p} })
}

const currentPage = () => {
  return route.params.page ? Number(route.params.page) : 1;
}

const limit = ref(20);
const board = ref<IBoard>();
const plots = ref<IPlot[]>();
const id: number = Number(route.params.id);

const paginator = (f?: string) => {
  if (f === 'active') {
    if (currentPage() === 1) {
      return (plots.value?.length === limit.value)
    }
    return true;
  }

  if (f === 'isPrev') {
    return (currentPage() > 1);
  }

  if (f === 'isNext') {
    return (plots.value?.length === limit.value);
  }

  if (f === 'next') {
    next();
  }

  if (f === 'prev') {
    prev();
  }
}

store.dispatch('loading');
api.get<IBoard>({ resource: BOARD, id: id }).then((response) => {
  if (response?.body) {
    board.value = response.body;
    store.dispatch("setTitle", board.value.name);
  }
}).then(() =>{
  api.get<IPlot[]>({ resource: PLOT, query: `?page=${currentPage()}&limit=${limit.value}&order%5Bupdated_at%5D=asc&board.id=${id}`}).then((response) => {
  if (response?.body) {
    plots.value = response.body;
  }
  store.dispatch('loaded');
});
});



</script>

<template>
  <div class="f-grow" v-if="board && plots" :key="routeName">
    <BoardComponent :boxes="1" :loading="store.state.loading" :board="board" :plots="plots" :paginator="paginator" />
  </div>
</template>
