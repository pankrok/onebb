<script setup lang="ts">
import { useUser } from '@/hooks/obbClient'
import type { IUser } from '@/interfaces'
import { ref } from 'vue'
import AvatarComponent from '@/components/ui/elements/AvatarComponent.vue'
import parseUsername from '@/utils/parseUsername'
import { $t } from '@/utils/i18n'
import useUserStore from '@/stores/useUserStore'
import Cropper from '@/components/ui/partials/Cropper.vue'
import ModalComponent from '@/components/ui/elements/ModalComponent.vue'
import useState from '@/utils/useState'

const data = ref()
const page = ref(1)
const canLoad = ref(true)
const [isOpen, setIsOpen] = useState(false)
const userStore = useUserStore()
const { getUser, getUserPosts, userPosts } = useUser()
getUser().then((response: IUser | undefined) => {
  data.value = response
})
getUserPosts().then((response) => {
  canLoad.value = response ?? false
  page.value++
})

async function getPostWrapper() {
  const response = await getUserPosts(String(page.value))
  canLoad.value = response ?? false
  page.value++
  console.log({ response })
}

function avatarUpdateHandler(avatar: string) {
  setIsOpen(false)
  data.value.avatar = avatar
}
</script>
<template>
  <div class="col-12 row" v-if="data">
    <ModalComponent
      :is-active="isOpen"
      :on-close="
        () => {
          setIsOpen(false)
        }
      "
    >
      <Cropper @update="avatarUpdateHandler" />
    </ModalComponent>
    <section class="col-sm-3 column-sm margin-sm-bottom-m margin-bottom-l">
      <div
        class="column mobile-row align-sm-items-center padding-sm-s padding-m font-size-14 font-weight-600"
      >
        <div class="position-relative">
          <span
            v-if="userStore.getUserId == data.id"
            class="position-absolute"
            style="bottom: 10px; right: 10px"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              height="1.5em"
              class="cursor-pointer"
              @click="setIsOpen(true)"
              fill="white"
              viewBox="0 0 512 512"
            >
              <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
              <path
                d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z"
              />
            </svg>
          </span>
          <AvatarComponent
            :url="data.avatar"
            size="img-size-l"
            mobile-size="img-size-mobile-m"
            class="padding-sm-y-l"
          />
        </div>
        <span
          class="color-blue text-shadow-blue font-size-16 padding-bottom-l margin-sm-left-m"
          v-html="parseUsername(data)"
        ></span>
        <div class="row col-12 justify-content-center display-flex display-sm-none">
          <span>{{ $t('Posts') }}:</span>
          <span class="color-blue margin-left-s"> {{ data.posts_no ?? 0 }}</span>
        </div>
        <div class="row col-12 justify-content-center display-flex display-sm-none">
          <span>{{ $t('Plots') }}:</span>
          <span class="color-blue margin-left-s"> {{ data.plots_no ?? 0 }}</span>
        </div>
      </div>
    </section>
    <section class="col-9 column">
      <div
        v-for="post in userPosts"
        class="column-sm col-12 margin-sm-y-s border-1 background-primary border-color-dark padding-sm-m"
        :key="post.id"
      >
        <router-link
          :to="{
            name: 'Plot',
            params: { slug: post.plot.slug, id: post.plot.id, page: 1 }
          }"
          class="font-size-20 font-weigt-600 margin-sm-y-m"
          >{{ post.plot.name }}</router-link
        >
        <div v-html="post.content"></div>
      </div>

      <button
        v-if="canLoad"
        class="button button-color-dark background-primary color-white"
        @click="getPostWrapper"
      >
        {{ $t('Load more') }}
      </button>
    </section>
  </div>
</template>
