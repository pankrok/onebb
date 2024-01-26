<script setup lang="ts">
import type { IMessage } from '@/interfaces'
import AvatarComponent from '../elements/AvatarComponent.vue'
import useMoment from '@/hooks/useMoment'

const { parse } = useMoment()
defineProps<IMessage>()
</script>

<template>
  
    <span class="col-12 font-size-14 font-weight-100 color-white padding-bottom-s"
      >{{ sender.username }}</span
    >
    <AvatarComponent
      :url="sender.avatar"
      size="img-size-s"
      mobile-size="img-size-mobile-s"
      class="margin-right-s"
    />
    
    <span
      class="flex-1 border-1 border-color-blue border-radius-5 background-blue color-black font-size-14 padding-s tooltip"
      >{{ message }}<span class="tooltiptext">{{ parse(created_at) }}</span></span
    >
  
</template>
