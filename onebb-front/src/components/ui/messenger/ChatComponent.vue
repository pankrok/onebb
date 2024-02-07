<script setup lang="ts">
import { useMessenger } from '@/hooks/obbClient'
import useUserStore from '@/stores/useUserStore'
import MsgComponent from './MsgComponent.vue'
import UserMsgComponent from './UserMsgComponent.vue'
import NewMsgDivider from './NewMsgDivider.vue'
import { ref } from 'vue'
import useMessengerStore from '@/stores/useMessengerStore'
import { onMounted } from 'vue'
import { onUnmounted } from 'vue'

const msg = ref('')
const obbChat = ref()
const interval = ref()
const userStore = useUserStore()
const messengerStore = useMessengerStore()
const messenger = useMessenger()

function scrollDown() {
  obbChat.value.scrollTo({
    top: obbChat.value.scrollHeight,
    behavior: 'smooth'
  })
}

messenger.getMessages().then(() => {
  scrollDown()
})

async function sendMessage() {
  await messenger.sendMessage(msg.value)
  msg.value = ''
  scrollDown()
}

onMounted(() => {
  interval.value = setInterval(async () => {
    const newData = await messenger.getNewMessages()
    if (newData) {
      scrollDown()
    }
  }, 2000)
})

onUnmounted(() => {
  clearInterval(interval.value)
})
</script>

<template>
  <div class="flex-grow-1 column-sm padding-sm-m flex-no-wrap" style="overflow: auto" ref="obbChat">
    <div
      v-for="message in messengerStore.messages"
      class="row-sm justify-content-flex-start align-items-center padding-m"
      :class="[
        message.sender.id == userStore.getUserId
          ? 'justify-content-flex-start'
          : 'justify-content-flex-end'
      ]"
    >
      <UserMsgComponent v-if="message.sender.id == userStore.getUserId" v-bind="message" />
      <MsgComponent v-else v-bind="message" />
    </div>
  </div>
  <div class="row-sm">
    <div class="col-sm-8 column-sm">
      <input type="text" class="form-control color-white font-weight-400" v-model="msg" />
    </div>
    <div class="col-sm-4 column-sm">
      <button class="button color-white" @click="sendMessage">Send</button>
    </div>
  </div>
</template>
