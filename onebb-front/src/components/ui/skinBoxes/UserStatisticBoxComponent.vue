<script setup lang="ts">
import { useUser } from '@/hooks/obbClient'
import useUserStore from '@/stores/useUserStore'
import { onMounted } from 'vue'
import { ref } from 'vue'
import PanelComponent from '../elements/PanelComponent.vue'
import { $t } from '@/utils/i18n'
import parseUsername from '@/utils/parseUsername'
import AvatarComponent from '@/components/ui/elements/AvatarComponent.vue'
import { watch } from 'vue'
import { storeToRefs } from 'pinia'

const userStore = useUserStore()

const { user, logged } = storeToRefs(userStore)
const { getUser } = useUser()
const data = ref()

async function getUserData() {
  console.log({ getUserId: userStore.getUserId, logged })

  if (!logged.value) {
    data.value = null
    return
  }

  if (userStore.getUserId !== 0) {
    data.value = await getUser(userStore.getUserId)
    return
  }

  setTimeout(() => {
    getUserData()
  }, 500)
}

getUserData()
watch(user, () => {
  getUserData()
})
</script>

<template>
  <PanelComponent header>
    <template #header>
      <h4 class="font-size-18 font-weight-400 margin-y-s color-light">
        {{ $t('User statistics') }}
      </h4>
    </template>
    <section class="col-12 column-sm margin-sm-bottom-m margin-bottom-l">
      <div
        v-if="data"
        class="column mobile-row align-sm-items-center padding-sm-s padding-m font-size-14 font-weight-600"
      >
        <div class="position-relative">
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
      <div
        v-else
        class="column mobile-row align-sm-items-center padding-sm-s padding-m font-size-14 font-weight-600"
      >
        {{ $t('hello stranger') }}
      </div>
    </section>
  </PanelComponent>
</template>
