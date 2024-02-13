<script setup lang="ts">
import type { IPlot, IPost } from '@/interfaces'
import JoditComponent from './JoditComponent.vue'
import useUserStore from '@/stores/useUserStore'
import { ref } from 'vue'
import parseUsername from '@/utils/parseUsername'
import useMoment from '@/hooks/useMoment'
import PaginatorComponent from './PaginatorComponent.vue'
import { $t } from '@/utils/i18n'
import type { HydraView } from '@/interfaces/config'
import AvatarComponent from '../elements/AvatarComponent.vue'
import ModalComponent from '../elements/ModalComponent.vue'
import instanceOf from '@/utils/instanceOf'

const emit = defineEmits<{
  (e: 'submit', value: string): void
  (e: 'mod', id: number, value: { content?: string; hidden?: boolean }): void
}>()

defineProps<{
  posts: IPost[]
  plot: IPlot
  paginator?: HydraView
}>()

const openEditor = ref(false)
const modModal = ref(false)
const modData = ref<IPost | null>(null)
const textValue = ref('')
const userStore = useUserStore()
const { parse } = useMoment()

function modPost(payload: IPost) {
  modModal.value = true
  modData.value = payload
}

function modSave() {
  if (instanceOf<IPost>(modData.value)) {
    console.log({ modData: modData.value })
    emit('mod', modData.value?.id, { content: modData.value.content, hidden: modData.value.hidden })
  }
}

function replay() {
  if (openEditor.value === false) {
    openEditor.value = true
    return
  }

  emit('submit', textValue.value)
  textValue.value = ''
}
</script>
<template>
  <div class="col-12" v-if="plot">
    <section class="col-sm-auto column-sm margin-sm-bottom-m margin-bottom-l">
      <h1 class="margin-sm-y-s">{{ plot.name }}</h1>
      <span class="font-size-12"
        ><router-link
          :to="{
            name: 'Profile',
            params: {
              slug: plot.user.slug,
              id: plot.user.id
            }
          }"
          v-html="parseUsername(plot.user)"
        ></router-link>
        {{ parse(plot.updated_at) }}</span
      >
    </section>
    <section class="row col-12 justify-sm-content-space-between align-sm-items-center">
      <div class="justify-content-flex-end">
        <button
          v-if="userStore.logged"
          class="button button-color-green margin-right-m"
          @click="replay"
        >
          {{ $t('replay') }}
        </button>
      </div>
      <div>
        <paginator-component :hydra-view="paginator" />
      </div>
    </section>
    <section class="">
      <div
        v-for="post in posts"
        :key="post.id"
        class="row col-12 margin-sm-y-m margin-y-l back border-1 background-primary border-color-dark"
      >
        <router-link
          :to="{
            name: 'Profile',
            params: {
              slug: post.user.slug,
              id: post.user.id
            }
          }"
          class="col-2 col-sm-auto"
        >
          <div
            class="column mobile-row align-sm-items-center padding-sm-s padding-m font-size-14 font-weight-600"
          >
            <AvatarComponent
              :url="post.user.avatar"
              :alt="post.user.username"
              size="img-size-l"
              mobile-size="img-size-mobile-m"
              class="padding-sm-y-l"
            />
            <span
              class="color-blue text-shadow-blue font-size-16 padding-bottom-l margin-sm-left-m"
              v-html="parseUsername(post.user)"
            ></span>
            <div class="row col-12 justify-content-center display-flex display-sm-none">
              <span>{{ $t('Posts') }}:</span>
              <span class="color-blue margin-left-s"> {{ post.user.posts_no ?? 0 }}</span>
            </div>
            <div class="row col-12 justify-content-center display-flex display-sm-none">
              <span>{{ $t('Plots') }}:</span>
              <span class="color-blue margin-left-s"> {{ post.user.plots_no ?? 0 }}</span>
            </div>
          </div>
        </router-link>
        <div class="col-10 col-sm-12 column justify-content-space-between">
          <div
            class="row justify-content-space-between align-items-center font-size-12 margin-bottom-l display-flex display-sm-none"
          >
            <div class="color-light margin-top-s">{{ parse(post.created_at) }}</div>
            <div>
              <button
                class="button button-color-yellow"
                v-if="userStore.mod"
                @click="modPost(post)"
              >
                {{ $t('edit') }}
              </button>
              <!-- <button class="button button-color-green">{{ $t('Quote') }}</button> -->
            </div>
          </div>
          <div
            class="col-auto color-white padding-top-s padding-x-none padding-sm-s font-size-14"
            v-html="post.content"
          ></div>
          <!-- <div class="row border-top-1 border-color-dark margin-top-l padding-s">
            This is my signature
          </div> -->
        </div>
      </div>
    </section>
    <Transition mode="in-out" name="fade">
      <section v-if="openEditor" class="row col-12 margin-y-s" key="editor">
        <jodit-component
          v-if="userStore.logged"
          :value="textValue"
          @update-event="
            (e) => {
              textValue = e
            }
          "
        />
      </section>
    </Transition>
    <section class="row col-12 margin-top-l justify-content-space-between align-items-center">
      <div class="justify-content-flex-end">
        <button v-if="userStore.logged" class="button button-color-green" @click="replay">
          Odpowiedz
        </button>
      </div>
      <div>
        <paginator-component :hydra-view="paginator" />
      </div>
    </section>
  </div>
  <ModalComponent
    v-if="modModal && modData"
    :on-close="
      () => {
        modModal = false
      }
    "
  >
    <jodit-component
      v-if="userStore.mod"
      :value="modData?.content"
      @update-event="
        (e) => {
          if (modData) modData.content = e
        }
      "
    />
    {{ $t('Hide post') }}
    <label for="hidden" class="switch">
      <input id="hidden" type="checkbox" class="" v-model="modData.hidden" />
      <span class="slider round"></span>
    </label>

    <button class="button button-dark color-white margin-sm-top-m" @click="modSave">
      {{ $t('submit') }}
    </button>
  </ModalComponent>
</template>
