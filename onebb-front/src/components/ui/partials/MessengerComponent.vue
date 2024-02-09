<script setup lang="ts">
import StartChatComponent from '@/components/ui/messenger/StartChatComponent.vue'
import ChatComponent from '../messenger/ChatComponent.vue'
import ListComponent from '../messenger/ListComponent.vue'
import useMessengerStore from '@/stores/useMessengerStore'
import { MessengerComponentEnum } from '@/hooks/obbClient'
import { $t } from '@/utils/i18n'
import { computed } from 'vue'
import { storeToRefs } from 'pinia'


const messengerComponents = [ListComponent, ChatComponent, StartChatComponent]
const messengerStore = useMessengerStore()

</script>
<template>
  <Teleport to="#app">
    <Transition mode="in-out" name="slide-fade">
      <aside v-if="messengerStore.showMessenger" class="messenger column padding-m">
        <div
          class="column-sm flex-no-wrap justify-sm-content-space-between border-1 border-color-primary border-radius-5 background-background box-shadow-light"
          style="height: 450px; width: 400px"
        >
          <div
            class="row-sm justify-sm-content-flex-end border-bottom-1 border-color-primary background-stripes"
          >
            <button
              class="button button-color-white margin-sm-x-s"
              @click="messengerStore.setComponent(MessengerComponentEnum.Add)"
            >
              {{ $t('new') }}
            </button>
            <button
              class="button button-color-white margin-sm-x-s"
              @click="messengerStore.setComponent(MessengerComponentEnum.List)"
            >
              {{ $t('list') }}
            </button>
            <button class="button button-color-white" @click="messengerStore.toggleMessenger">
              {{ $t('close') }}
            </button>
          </div>

            <component
              :is="messengerComponents[messengerStore.component]"
              :key="`messenger-component-${messengerStore.component.toString()}`"
            />
 
        </div>
      </aside>
    </Transition>
  </Teleport>
</template>
