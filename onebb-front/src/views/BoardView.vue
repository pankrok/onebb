<script setup lang="ts">
import { useRoute, useRouter, RouteRecordName } from "vue-router";
import { useStore } from "vuex";
import { ref, watch, computed } from "vue";
import BoardComponent from "@/components/ui/partial/Board.vue";
import { IBoard, IPlot } from "@/interfaces/obbApiInterface";
import { BOARD, PLOT } from "@/services/api/obbResources";
import api from "@/services/api/api";
import {usePaginator, currentPage} from '@/services/helpers/parsers';

const store = useStore();
const route = useRoute();
const router = useRouter();
const routeName = ref<RouteRecordName>("");

const limit = ref(20);
const board = ref<IBoard>();
const plots = ref<IPlot[]>();
const id: number = Number(route.params.id);
const paginator = usePaginator(plots, 'Board', route, router);

store.dispatch('loading');
api.get<IBoard>({ resource: BOARD, id: id }).then((response) => {
  if (response?.body) {
    board.value = response.body;
    store.dispatch("setTitle", board.value.name);
  }
}).then(() =>{
  api.get<IPlot[]>({ resource: PLOT, query: `?page=${currentPage(route)}&limit=${limit.value}&order%5Bupdated_at%5D=asc&board.id=${id}`}).then((response) => {
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
