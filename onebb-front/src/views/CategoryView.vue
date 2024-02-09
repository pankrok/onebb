<script setup lang="ts">
import CategoryComponent from '@/components/ui/partials/CategoryComponent.vue'
import { useCategory } from '@/hooks/obbClient'
import type { ICategory, IHydra } from '@/interfaces'
import instanceOf from '@/utils/instanceOf'
import { ref } from 'vue'
import { $t } from '@/utils/i18n'
import useTimelineStore from '@/stores/useTimelineStore'
import useState from '@/utils/useState'

const timelineStore = useTimelineStore()
const categories = ref<ICategory[] | null>(null)
const [isRead, setIsRead] = useState(false)
const categoryName = ref('')
useCategory().then((response) => {
  console.log({ response })
  if (instanceOf<ICategory[]>(response)) {
    categories.value = response
    categoryName.value = response[0].name
    categories.value[0].name = $t('board')
    setIsRead(timelineStore.getCategoryTimeline(response[0].id, response[0].updated_at))
    timelineStore.setCategoryTimeline(response[0].id)
  }
})
</script>

<template>
  <section class="row col-auto">
    <h1 class="margin-s">
      {{ categoryName }}
    </h1>
    <CategoryComponent
      v-for="category in categories"
      :key="category.id"
      :category="category"
      :is-read="isRead"
    />
  </section>
</template>
