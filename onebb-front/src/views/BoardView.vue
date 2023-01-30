<script setup lang="ts">
import { useRoute, RouteRecordName } from "vue-router";
import { useStore } from "vuex";
import { ref, computed, onBeforeMount } from "vue";
import BoardComponent from "@/components/ui/partial/Board.vue";
import { IBoard } from "@/interfaces/obbApiInterface";
import { BOARD } from "@/services/api/obbResources";
import api from "@/services/api/api";

const store = useStore();
const route = useRoute();
const routeName = ref<RouteRecordName>("");

const board = ref<IBoard>();
const id: number = Number(route.params.id);

store.dispatch('loading');
api.get<IBoard>({ resource: BOARD, id: id }).then((response) => {
  if (response?.body) {
    board.value = response.body;
    store.dispatch("setTitle", board.value.name);
  }
  store.dispatch('loaded');
});
</script>

<template>
  <div class="f-grow" v-if="board" :key="routeName">
    <BoardComponent :boxes="1" :loading="store.state.loading" :board="board" />
  </div>
</template>
