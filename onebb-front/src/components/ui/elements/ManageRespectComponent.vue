<script setup lang="ts">
import useRespect from '@/hooks/obbClient/useRespect'
import type { IRespect } from '@/interfaces'
import useState from '@/utils/useState'
import { ref, watch } from 'vue'
import ModalComponent from './ModalComponent.vue'
import AvatarComponent from './AvatarComponent.vue'
import parseUsername from '@/utils/parseUsername'
import useMoment from '@/hooks/useMoment'
import useAlertStore from '@/stores/useAlertStore'
import { $t } from '@/utils/i18n'
import useUserStore from '@/stores/useUserStore'
import { storeToRefs } from 'pinia'

const { postId, respectNo } = defineProps<{
  postId: number
  respectTo: number
  respectNo: number
  hideAddRespect?: boolean
}>()

const { getUserId, logged } = storeToRefs(useUserStore())
const { setAlert } = useAlertStore()
const postRespect = ref(respectNo)
const { parse } = useMoment()
const { showPostRespect, manageRespect } = useRespect()
const [respectsModal, setRespectsModal] = useState<boolean>(false)
const [allRespects, setAllRespects] = useState<IRespect[]>()
const [addRespectModal, setAddRespectModal] = useState(false)
const respectTypes: ['thumb', 'smile', 'heart'] = ['thumb', 'smile', 'heart']
interface IAddRespect {
  postId: number
  respectTo: number
  type: 'smile' | 'thumb' | 'heart'
}
async function manageRespectWrapper(payload: IAddRespect) {
  const { status, type } = await manageRespect(payload)
  console.log({ status, type })
  if (status > 299) {
    setAlert({
      type: 'alert-danger',
      message: $t('there was a problem with add respect for this post'),
      timeout: 5
    })
    return
  }

  if (type === 'put') {
    setAlert({
      type: 'alert-info',
      message: $t('Your respect emot is updated'),
      timeout: 5
    })
  }

  if (type === 'post') {
    postRespect.value++
    setAlert({
      type: 'alert-info',
      message: $t('You pay respect for this post'),
      timeout: 5
    })
  }

  if (type === 'delete') {
    postRespect.value--
    setAlert({
      type: 'alert-info',
      message: $t('You remove your respect for this post'),
      timeout: 5
    })
  }
}

watch(respectsModal, async () => {
  console.log('respectsModal', respectsModal.value)
  if (respectsModal.value && !allRespects.value) {
    const handler = await showPostRespect(postId)
    if (handler) setAllRespects(handler)
  }
})
</script>
<template>
  <div>
    <div class="row-sm alige-sm-items-center margin-sm-y-s position-relative">
      <button 
        class="button button-color-dark background-primary color-white margin-right-m margin-sm-right-l"
        @click="setRespectsModal(true)"
      >
        {{ postRespect }}
      </button>
      <svg
      v-if="respectTo != getUserId && logged && !hideAddRespect"
        class="cursor-pointer"
        height="1.5rem"
        fill="white"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 512 512"
        @click="setAddRespectModal(!addRespectModal)"
      >
        <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
        <path
          d="M323.8 34.8c-38.2-10.9-78.1 11.2-89 49.4l-5.7 20c-3.7 13-10.4 25-19.5 35l-51.3 56.4c-8.9 9.8-8.2 25 1.6 33.9s25 8.2 33.9-1.6l51.3-56.4c14.1-15.5 24.4-34 30.1-54.1l5.7-20c3.6-12.7 16.9-20.1 29.7-16.5s20.1 16.9 16.5 29.7l-5.7 20c-5.7 19.9-14.7 38.7-26.6 55.5c-5.2 7.3-5.8 16.9-1.7 24.9s12.3 13 21.3 13L448 224c8.8 0 16 7.2 16 16c0 6.8-4.3 12.7-10.4 15c-7.4 2.8-13 9-14.9 16.7s.1 15.8 5.3 21.7c2.5 2.8 4 6.5 4 10.6c0 7.8-5.6 14.3-13 15.7c-8.2 1.6-15.1 7.3-18 15.2s-1.6 16.7 3.6 23.3c2.1 2.7 3.4 6.1 3.4 9.9c0 6.7-4.2 12.6-10.2 14.9c-11.5 4.5-17.7 16.9-14.4 28.8c.4 1.3 .6 2.8 .6 4.3c0 8.8-7.2 16-16 16H286.5c-12.6 0-25-3.7-35.5-10.7l-61.7-41.1c-11-7.4-25.9-4.4-33.3 6.7s-4.4 25.9 6.7 33.3l61.7 41.1c18.4 12.3 40 18.8 62.1 18.8H384c34.7 0 62.9-27.6 64-62c14.6-11.7 24-29.7 24-50c0-4.5-.5-8.8-1.3-13c15.4-11.7 25.3-30.2 25.3-51c0-6.5-1-12.8-2.8-18.7C504.8 273.7 512 257.7 512 240c0-35.3-28.6-64-64-64l-92.3 0c4.7-10.4 8.7-21.2 11.8-32.2l5.7-20c10.9-38.2-11.2-78.1-49.4-89zM32 192c-17.7 0-32 14.3-32 32V448c0 17.7 14.3 32 32 32H96c17.7 0 32-14.3 32-32V224c0-17.7-14.3-32-32-32H32z"
        />
      </svg>
      <Transition name="fade" mode="in-out">
        <div v-if="addRespectModal" class="position-absolute" style="bottom: 32px">
          <button
            v-for="respectType in respectTypes"
            class="button button-color-dark background-primary color-white"
            @click="manageRespectWrapper({ postId, respectTo, type: respectType })"
          >
            <AvatarComponent
              :url="`assets/respect/${respectType}.svg`"
              :alt="respectType"
              size="img-size-s"
              mobile-size="img-size-mobile-s"
            />
          </button>
        </div>
      </Transition>
    </div>
    <ModalComponent
      :on-close="() => setRespectsModal(false)"
      :is-active="respectsModal"
      wrapper-class="col-sm-10"
      v-if="respectsModal"
    >
      <div class="row">
        <div class="col-6 row-sm" v-for="respect in allRespects">
          <div class="col-sm-2 column align-items-center">
            <AvatarComponent
              :url="respect.respectFrom.avatar"
              :alt="respect.respectFrom.username"
              size="img-size-m"
              mobile-size="img-size-mobile-m"
            />
          </div>
          <div class="col-sm-10 column-sm justify-content-start">
            <span class="margin-sm-bottom-m" v-html="parseUsername(respect.respectFrom)"></span>
            <span
              ><AvatarComponent
                :url="`assets/respect/${respect.type}.svg`"
                :alt="respect.type"
                size="img-size-s"
                mobile-size="img-size-mobile-s"
              />
              {{ parse(respect.createAt) }}</span
            >
          </div>
        </div>
      </div>
    </ModalComponent>
  </div>
</template>
