<script setup lang="ts">
import useCategory from '@/hooks/useCategory'
import Box from '@/components/box/BoxComponent.vue'
import CategoryComponent from '@/components/ui/CategoryComponent.vue'
import { ref } from 'vue'

const home = ref()

useCategory().then((data) => {
  home.value = data
})

const boxStyle: string[] = ['row', 'justify-content-space-between']
</script>

<template>
  <div class="column align-items-center justify-content-center" v-if="home" :key="$route.fullPath">
    <Box
      v-for="category in home"
      :key="category.id + category.name"
      :wrapperClass="['margin-y-s col-12']"
      :header="true"
    >
      <template #header>
        <router-link
          :to="{
            name: 'Category',
            params: { slug: category.slug, id: category.id }
          }"
        >
          <h3 class="margin-y-s margin-x-m">{{ category.name }}</h3>
        </router-link>
      </template>
      <p class="margin-m">
        <Box v-for="board in category.boards" :key="board.id + board.name" :boxClass="boxStyle">
          <CategoryComponent :board="board" />
        </Box>
      </p>
    </Box>
  </div>
</template>
