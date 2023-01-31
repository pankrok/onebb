<script setup lang="ts">
import { useStore } from "vuex";
import { ref } from "vue";
import { ICategory } from "@/interfaces/obbApiInterface";
import { CATEGORY } from "@/services/api/obbResources";
import Category from "@/components/ui/partial/Category.vue";
import api from "@/services/api/api";

const store = useStore();
const categories = ref<ICategory[]>();

store.dispatch("setTitle", "Home");
api.get<ICategory[]>({ resource: CATEGORY }).then((response) => {
  if (response?.body) {
    categories.value = response.body;
  }
  store.dispatch("loaded");
});
</script>

<template>
  <Category
    v-if="categories"
    v-for="category in categories"
    :category="category"
  />
</template>
