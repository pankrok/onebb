<script setup lang="ts">
import StartChatComponent from '@/components/ui/messenger/StartChatComponent.vue'
import ChatComponent from '../messenger/ChatComponent.vue'
import ListComponent from '../messenger/ListComponent.vue'
import useMessengerStore from '@/stores/useMessengerStore'
import { MessengerComponentEnum } from '@/hooks/obbClient'
import { $t } from '@/utils/i18n'

const messengerComponents = [
  ListComponent,
  ChatComponent,
  StartChatComponent,
]

const messengerStore = useMessengerStore()
</script>
<template>
  <Teleport to="#app">
    <aside class="messenger column padding-m">
      <div
        class="column justify-content-space-between border-1 border-color-primary border-radius-5 background-background box-shadow-light"
        style="height: 450px; width: 400px"
      >
        <div
          class="row justify-content-flex-end border-bottom-1 border-color-primary background-stripes"
        >
          <button class="button button-color-white" @click="messengerStore.toggleMessenger">
            {{ $t('close') }}
          </button>
        </div>
        <component :is="messengerComponents[messengerStore.component]" />
      </div>
    </aside>
  </Teleport>
</template>
