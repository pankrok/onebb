<script setup lang="ts">
import { ref } from 'vue';
import { useRoute } from 'vue-router';
import useCategory from '@/hooks/useCategory';
import Box from '@/components/box/BoxComponent.vue';
import CategoryComponent from '@/components/ui/CategoryComponent.vue';
import type { ICategory } from '@/interfaces/OnebbInterfaces';
import { useUser } from '@/hooks/useUser';

const route = useRoute();
const user = useUser();
const home = ref<ICategory[]>([]);
useCategory(route.params.id ?? undefined).then(categories => {
  // @ts-ignore
  home.value = categories['hydra:member'] ? categories['hydra:member'] : [categories];
  console.log({ response: home.value })
})

const boxStyle: string[] = [
  "row",
  'justify-content-space-between'
];

</script>

<template>
  <div class="column" v-if="home" :key="route.fullPath">
    <Box v-for="category in home" header>
      <template #header>
        <router-link :to="{
            name: 'Category',
            params: { slug: category.slug, id: category.id },
          }">
          <h3 class="margin-y-s margin-x-m">{{ category.name }}</h3>
        </router-link>
      </template>
      <p class="margin-m">
        <Box v-for="board in category.boards" :boxClass="boxStyle">
          <CategoryComponent :board="board" />
        </Box>
      </p>
    </Box>
  </div>
</template>
