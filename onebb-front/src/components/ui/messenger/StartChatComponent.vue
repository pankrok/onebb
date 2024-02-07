<script setup lang="ts">
import AvatarComponent from '../elements/AvatarComponent.vue'
import useMessengerStore from '@/stores/useMessengerStore'
import { useMessenger, MessengerComponentEnum } from '@/hooks/obbClient'
import { ref } from 'vue'
import { watch } from 'vue'
import parseUsername from '@/utils/parseUsername'
import type { IUser } from '@/interfaces'
import { $t } from '@/utils/i18n'

const messengerStore = useMessengerStore()
const { findUser } = useMessenger()

const searchUserString = ref('')
const selectedUserList = ref<IUser[]>([])

watch(searchUserString, () => {
  findUser(searchUserString.value)
})

function addUser(payload: IUser) {
  if (selectedUserList.value.includes(payload) === false) selectedUserList.value.push(payload)
}

function removeUser(payload: IUser) {
  selectedUserList.value = selectedUserList.value.filter((el) => el != payload)
}

function startChat() {
  messengerStore.setChatUsers(selectedUserList.value)
  messengerStore.setComponent(MessengerComponentEnum.Chat)
}
</script>
<template>
  <div v-if="selectedUserList" class="row padding-sm-y-m border-bottom-1 border-color-light">
    <div class="col-12 font-size-14 font-weight-600 text-align-center margin-sm-bottom-m">
      {{ $t('selected users') }}:
    </div>
    <div
      v-for="user in selectedUserList"
      :key="`select_${user.id}`"
      @click="removeUser(user)"
      class="cursor-pointer"
    >
      <span v-html="parseUsername(user)" class="margin-sm-x-s"></span>
    </div>
  </div>
  <div class="column-sm flex-grow-1 margin-sm-y-m" style="overflow-y: scroll">
    <div
      v-if="messengerStore.userList"
      v-for="user in messengerStore.userList"
      @click="addUser(user)"
      :key="`search_${user.id}`"
      class="cursor-pointer row-sm align-items-center"
    >
      <AvatarComponent :url="user.avatar" size="img-size-m" mobile-size="img-size-mobile-s" />
      <span v-html="parseUsername(user)"></span>
    </div>
  </div>
  <div class="row-sm">
    <div class="col-sm-8 column-sm">
      <input
        type="text"
        class="form-control color-white font-weight-400"
        v-model="searchUserString"
      />
    </div>
    <div class="col-sm-4 column-sm">
      <button class="button button-color-green" @click="startChat">
        {{ $t('start chat') }}
      </button>
    </div>
  </div>
</template>
