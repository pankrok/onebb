<script setup lang="ts">
import { useStore } from "vuex";
import { useRoute } from "vue-router";
import { onBeforeUnmount, onBeforeMount, ref } from "vue";
import { ICategory } from "@/interfaces/obbApiInterface";
import Category from "@/components/ui/partial/Category.vue";
import api from "@/services/api/api";
import {CATEGORY} from '@/services/api/obbResources';

const store = useStore();
const route = useRoute();
const category = ref<ICategory>();
const id: number = Number(route.params.id);

store.dispatch('loading');

api.get<ICategory>({ resource: CATEGORY, id: id }).then((response) => {
  if (response?.body) {
    category.value = response.body;
    store.dispatch("setTitle", category.value.name);
  }
  store.dispatch('loaded');
});

</script>

<template>
  <Category v-if="category" :category="category" />
</template>
