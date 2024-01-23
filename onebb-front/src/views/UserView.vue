<script setup lang="ts">
import { useUser } from '@/hooks/obbClient'
import type { IUser } from '@/interfaces'
import { ref } from 'vue'
import AvatarComponent from '@/components/ui/elements/AvatarComponent.vue'
import parseUsername from '@/utils/parseUsername'
import { $t } from '@/utils/i18n'

const data = ref()
const page = ref(1)
const canLoad = ref(true)
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
</script>
<template>
  <div class="col-12 row" v-if="data">
    <section class="col-sm-3 column-sm margin-sm-bottom-m margin-bottom-l">
      <div
        class="column mobile-row align-sm-items-center padding-sm-s padding-m font-size-14 font-weight-600"
      >
        <AvatarComponent
          :url="data.avatar"
          size="img-size-l"
          mobile-size="img-size-mobile-m"
          class="padding-sm-y-l"
        />
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
      <h3>{{$t('Plot')}}: {{ post.plot.name }}</h3>
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
