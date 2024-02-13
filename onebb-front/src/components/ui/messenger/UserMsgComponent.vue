<script setup lang="ts">
import type { IMessage } from '@/interfaces'
import AvatarComponent from '../elements/AvatarComponent.vue'
import useMoment from '@/hooks/useMoment'

const { parse } = useMoment()
defineProps<IMessage>()
</script>

<template>
    <span class="col-12 font-size-14 font-weight-100 color-white padding-bottom-s text-align-right"
      >{{ sender.username }}</span
    >

    <span
      class="flex-1 border-1 background-primary border-color-dark border-radius-5 font-size-14 padding-s text-align-right tooltip"
      >{{ message }}<span class="tooltiptext">{{ parse(created_at) }}</span></span
    >
    <AvatarComponent
      :url="sender.avatar"
      :alt="sender.username"
      size="img-size-s"
      mobile-size="img-size-mobile-s"
      class="margin-left-s"
    />
    
</template>
