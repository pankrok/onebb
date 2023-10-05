<script setup lang="ts">
import { defineProps } from 'vue'
import type { IPlot } from '@/interfaces'
import { useUser } from '@/hooks/useUser'
import { useMoment } from '@/hooks/useMoment'

const props = defineProps<{ plot: IPlot }>()
const { parseUsername } = useUser()
const moment = useMoment()
const updated_at = moment.parse(props.plot.updated_at)
</script>
<template>
  <div class="row margin-m">
    <div class="col-8 row align-items-center">
      <router-link
        :to="{
          name: 'Plot',
          params: { slug: plot.slug, id: plot.id, page: 1 }
        }"
      >
        <span class="font-weight-800">{{ plot.name }}</span>
      </router-link>
    </div>
    <div class="col-2 row align-items-center">
      <span>
        <i class="fas fa-comments"></i> {{ plot.post_no }} / {{ plot.views }}
        <i class="fas fa-eye"></i>
      </span>
    </div>
    <div v-if="plot.last_active_user" class="col-2 row">
      <div class="col-9 column text-align-end">
        <div v-html="parseUsername(plot.last_active_user)"></div>
        <div>
          {{ updated_at }}
        </div>
      </div>
      <div class="col-3 row align-items-center justify-content-flex-end">
        <Image
          :src="plot.last_active_user.avatar"
          :alt="plot.last_active_user.username"
          :size="[50, 50]"
          rounded
        />
      </div>
    </div>
  </div>
</template>
