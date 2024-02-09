<script setup lang="ts">
import PanelComponent from '../elements/PanelComponent.vue'
import AvatarComponent from '../elements/AvatarComponent.vue'
import type { ICategory } from '@/interfaces'
import useMoment from '@/hooks/useMoment'
import parseUsername from '@/utils/parseUsername'
import { $t } from '@/utils/i18n'
import useTimelineStore from '@/stores/useTimelineStore'

defineProps<{
  category?: ICategory
  isRead?: boolean
  indexNo?: number
}>()
const { getBoardTimeline } = useTimelineStore()
const { parse } = useMoment()
</script>
<template>
  <panel-component :header="true" v-if="category">
    <template #header>
      <div
        v-if="indexNo"
        class="col-sm-1 row align-sm-items-center justify-sm-content-center font-size-18 font-weight-600"
        :class="isRead ? 'color-white' : 'color-green'"
      >
        {{ indexNo }}
      </div>
      <router-link
        :to="{
          name: 'Category',
          params: { slug: category.slug, id: category.id, page: 1 }
        }"
        class="col-sm-auto font-size-18 font-weight-400 margin-y-s color-light"
      >
        {{ category.name }}
      </router-link>
      <div class="col-sm-1 row justify-sm-content-end align-sm-items-center">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          height="16"
          width="16"
          class="pointer fill-white"
          viewBox="0 0 512 512"
        >
          <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
          <path
            d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z"
          />
        </svg>
      </div>
    </template>
    <div
      v-for="board in category.boards"
      :key="board.id"
      class="row align-sm-items-center padding-x-m padding-sm-y-s border-bottom-1 border-color-dark"
    >
      <div class="col-sm-2 col-1 text-align-center">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          height="32"
          width="32"
          :class="
            getBoardTimeline(board.id, board.updated_at)
              ? ['fill-light', 'box-shadow-light', 'border-radius-circel']
              : ['fill-green', 'box-shadow-green', 'border-radius-circel']
          "
          viewBox="0 0 512 512"
        >
          <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
          <path
            d="M464 256A208 208 0 1 0 48 256a208 208 0 1 0 416 0zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256z"
          />
        </svg>
      </div>
      <div class="row col-sm-10 col-11 align-sm-items-center">
        <div class="col-7 row">
          <div class="col-12 row">
            <router-link
              :to="{
                name: 'Board',
                params: { slug: board.slug, id: board.id, page: 1 }
              }"
              class="line-heigh-18 font-weight-800 margin-bottom-m"
            >
              {{ board.name }}
            </router-link>
          </div>
          <div v-if="board.description" class="col-12 color-light" v-html="board.description"></div>
        </div>
        <div class="col-1 col-sm-4 column align-sm-items-center">
          <div class="col-12 color-light margin-bottom-m">
            <span class="font-size-14 font-weight-600">{{ $t('Plots').toLocaleUpperCase() }}</span>
          </div>
          <div class="col-12 color-white font-size-18 font-weight-800">
            {{ board.plots_no ?? 0 }}
          </div>
        </div>
        <div class="col-1 col-sm-4 column align-sm-items-center">
          <div class="col-12 color-light margin-bottom-m">
            <span class="font-size-14 font-weight-600">{{ $t('Posts').toLocaleUpperCase() }}</span>
          </div>
          <div class="col-12 color-white font-size-18 font-weight-800">
            {{ board.posts_no ?? 0 }}
          </div>
        </div>

        <RouterLink
          v-if="board.last_active_user"
          class="col-2 col-sm-4 row align-sm-items-center text-align-right"
          :to="{
            name: 'Profile',
            params: {
              slug: board.last_active_user.slug,
              id: board.last_active_user.id
            }
          }"
        >
          <div class="column col-7">
            <div
              class="col-12 row align-sm-items-center justify-sm-content-flex-end color-light margin-bottom-m"
            >
              <div class="font-size-16" v-html="parseUsername(board.last_active_user)"></div>
            </div>
            <div class="col-12 color-white font-size-14 font-weight-800">
              {{ parse(board.updated_at) }}
            </div>
          </div>
          <div class="col-5 display-flex display-sm-none">
            <AvatarComponent
              :url="board.last_active_user.avatar"
              size="img-size-m"
              mobile-size="img-size-mobile-s"
            />
          </div>
        </RouterLink>
      </div>
    </div>
  </panel-component>
</template>
