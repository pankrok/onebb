<script setup lang="ts">
import useMessengerStore from '@/stores/useMessengerStore'
import { MessengerComponentEnum, useMessenger } from '@/hooks/obbClient'
import AvatarComponent from '../elements/AvatarComponent.vue'
import useMoment from '@/hooks/useMoment'
import type { IOneMessenger } from '@/interfaces'
import moment from 'moment'
import { $t } from '@/utils/i18n'

const messengerStore = useMessengerStore()
const messenger = useMessenger()
const { parse } = useMoment()
messenger.getChats()

function setChat(chat: IOneMessenger) {
  messengerStore.setCurrentChat(chat)
  messengerStore.setComponent(MessengerComponentEnum.Chat)
}

function compare(date: string) {
    return moment(date) > moment(messengerStore.lastReadChat)
}

</script>

<template>
  <div class="flex-grow-1 column padding-sm-m">
    <div
      v-if="messengerStore.chats.length > 0"
      v-for="chat in messengerStore.chats"
      class="row align-items-center justify-content-space-between cursor-pointer"
      @click="setChat(chat)"
    >
    <div class="row align-items-center">
      <div v-for="user in chat.users" class="margin-sm-none">
        <AvatarComponent
          :url="user.avatar"
          size="img-size-s"
          mobile-size="mg-size-mobile-s"
          class="padding-none"
        />
      </div>
    </div>
      <div class="margin-sm-x-m">
        <span v-if="compare(chat.updated_at)"
          class="background-green border-1 border-radius-10 border-color-dark-green padding-sm-s font-size-12 font-weigh-600 color-white"
          >{{ $t('new').toUpperCase() }}</span
        >
        {{ parse(chat.updated_at) }}
        
      </div>
    </div>
  </div>
</template>
