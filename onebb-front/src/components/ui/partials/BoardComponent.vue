<script setup lang="ts">
import PanelComponent from '../elements/PanelComponent.vue'
import AvatarComponent from '../elements/AvatarComponent.vue'
import type { IBoard, IPlot } from '@/interfaces'
import useMoment from '@/hooks/useMoment'
import parseUsername from '@/utils/parseUsername'
import useUserStore from '@/stores/useUserStore'
import { $t } from '@/utils/i18n'
import PaginatorComponent from './PaginatorComponent.vue'
import type { HydraView } from '@/interfaces/config'
import useTimelineStore from '@/stores/useTimelineStore'

defineProps<{
  board?: IBoard
  plots?: IPlot[]
  paginator?: HydraView
}>()

function toLast(plot: IPlot) {
  if (getPlotTimeline(plot.id, plot.updated_at))
    return plot.post_no ? Math.ceil(plot.post_no / 20) : 1;

  return 1;
}

const { getPlotTimeline } = useTimelineStore()

const userStore = useUserStore()
const { parse } = useMoment()
</script>
<template>
  <section class="row col-auto align-items-center" v-if="board && plots">
    <h1 class="col-12 margin-y-s">
      {{ board.name }}
    </h1>

    <div v-if="userStore.logged" class="row margin-sm-y-m">
      <RouterLink
        class="button button-color-green margin-right-m"
        :to="{
          name: 'CreatePlot',
          params: {
            id: board.id
          }
        }"
        >{{ $t('Start new plot') }}
      </RouterLink>
    </div>
    <div class="col-sm-auto row justify-content-end">
      <paginator-component :hydra-view="paginator" />
    </div>
    <panel-component :header="true">
      <template #header>
        <span class="col-sm-auto font-size-18 font-weight-400 margin-y-s color-light">
          {{ $t('Plot') }}
        </span>
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
        v-for="plot in plots"
        :key="plot.id"
        class="row align-sm-items-center padding-x-m padding-y-s border-bottom-1 border-color-dark"
      >
        <div class="col-sm-2 col-1 text-align-center">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            height="32"
            width="32"
            :class="
              getPlotTimeline(plot.id, plot.updated_at)
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
        <div class="col-sm-10 col-11 row align-sm-items-center">
          <div class="col-7 row">
            <div class="col-12 row">
              <router-link
                :to="{
                  name: 'Plot',
                  params: { slug: plot.slug, id: plot.id, page: toLast(plot) }
                }"
                class="line-heigh-18 font-weight-800 margin-bottom-m"
              >
                {{ plot.name }}
              </router-link>
            </div>
            <div v-if="plot.user" class="col-12 color-light display-flex display-sm-none">
              {{ $t('by') }} <span class="margin-sm-left-m" v-html="parseUsername(plot.user)"></span>
            </div>
          </div>
          <div class="col-sm-4 col-1 column align-sm-items-center">
            <div class="col-12 color-light margin-bottom-m">
              <span class="font-size-14 font-weight-600">{{
                $t('Posts').toLocaleUpperCase()
              }}</span>
            </div>
            <div class="col-12 color-white font-size-18 font-weight-800">
              {{ plot.post_no ?? 0 }}
            </div>
          </div>
          <div class="col-sm-4 col-1 column align-sm-items-center">
            <div class="col-12 color-light margin-bottom-m">
              <span class="font-size-14 font-weight-600">{{
                $t('Views').toLocaleUpperCase()
              }}</span>
            </div>
            <div class="col-12 color-white font-size-18 font-weight-800">
              {{ plot.views ?? 0 }}
            </div>
          </div>
          <router-link
            :to="{
              name: 'Profile',
              params: {
                slug: plot.last_active_user.slug,
                id: plot.last_active_user.id
              }
            }"
            v-if="plot.last_active_user"
            class="col-sm-4 col-2 row align-sm-items-center text-align-right"
          >
            <div class="column col-7">
              <div
                class="col-12 row align-sm-items-center justify-sm-content-flex-end color-light margin-bottom-m"
              >
                <a href="#" class="font-size-16"
                  ><span v-html="parseUsername(plot.last_active_user)"></span
                ></a>
              </div>
              <div class="col-12 color-white font-size-14 font-weight-800">
                {{ parse(plot.updated_at) }}
              </div>
            </div>
            <div class="col-5 display-flex display-sm-none">
              <AvatarComponent
                :url="plot.last_active_user.avatar"
                :alt="plot.last_active_user.username"
                size="img-size-m"
                mobile-size="img-size-mobile-s"
              />
            </div>
          </router-link>
        </div>
      </div>
    </panel-component>
    <div class="col-sm-auto row justify-content-end">
      <paginator-component :hydra-view="paginator" />
    </div>
  </section>
</template>
