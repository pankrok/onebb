<script setup lang="ts">
import Modal from './ModalComponent.vue'
import { ref } from 'vue'
import PanelComponent from './PanelComponent.vue'
import { $t } from '@/utils/i18n'
import { useSearch } from '@/hooks/obbClient'
import AvatarComponent from '@/components/ui/elements/AvatarComponent.vue'
import parseUsername from '@/utils/parseUsername'
import useState from '@/utils/useState'

const { findPosts } = useSearch()
const [searchModal, setSearchModal] = useState(false)
const query = ref<string>('')
const data = ref()

function escapeRegExp(string: string) {
  return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') // $& means the whole matched string
}

function replaceAll(str: string, find: string, replace: string) {
  return str.replace(new RegExp(escapeRegExp(find), 'g'), replace)
}

function matchParser(text: string) {
  return replaceAll(
    text,
    query.value,
    `<span class="background-dark-yellow color-light-yellow padding-sm-x-s font-weight-600">${query.value}</span>`
  )
}

async function search() {
  // @ts-ignore
  data.value = await findPosts(query.value)
}
</script>
<template>
  <button class="button button-color-primary position-relative" @click="setSearchModal(true)">
    <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" fill="white" viewBox="0 0 512 512">
      <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
      <path
        d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"
      />
    </svg>
  </button>
  <Modal
    :is-active="searchModal"
    :on-close="
      () => {
        setSearchModal(false)
      }
    "
    :wrapper-class="['col-9', 'col-sm-10']"
    no-box
  >
    <PanelComponent header class="col-12">
      <template #header>
        <h4 class="font-size-18 font-weight-400 margin-y-s color-light">{{ $t('search') }}</h4>
      </template>
      <div class="column-sm margin-sm-m">
        <div class="row-sm align-items-center justify-content-space-between">
          <input
            class="form-control margin-sm-y-s col-8 color-white"
            type="text"
            :placeholder="$t('enter search phrase')"
            v-model="query"
          />
          <button type="button" class="button color-white margin-sm-y-s col-3" @click="search">
            {{ $t('find') }}
          </button>
        </div>
        <div v-if="data" style="max-height: 600px; overflow-y: auto; overflow-x: hidden;">
          <div
            class="row-sm margin-sm-s border-1 border-color-dark padding-sm-m"
            v-for="post in data['hydra:member']"
            :key="post.id"
          >
            <div class="col-sm-2">
              <router-link
                :to="{
                  name: 'Profile',
                  params: {
                    slug: post.user.slug,
                    id: post.user.id
                  }
                }"
                class="col-sm-2"
                @click="setSearchModal(false)"
              >
              <div
                class="column mobile-row align-sm-items-center padding-sm-s padding-m font-size-14 font-weight-600"
              >
                <AvatarComponent
                  :url="post.user.avatar"
                  size="img-size-m"
                  mobile-size="img-size-mobile-m"
                  class="padding-sm-y-l"
                />
                <span
                  class="color-blue text-shadow-blue font-size-16 padding-bottom-l margin-sm-left-m"
                  v-html="parseUsername(post.user)"
                ></span>
              </div>
              </router-link>
            </div>
            <div class="col-sm-10 column-sm">
              <router-link
                :to="{
                  name: 'Plot',
                  params: { slug: post.plot.slug, id: post.plot.id, page: 1 }
                }"
                class="col-12 font-size-18 font-weight-600 padding-sm-bottom-m border-bottom-1 border-color-dark"
                @click="setSearchModal(false)"
                v-html="matchParser(post.plot.name)"
              > 
              </router-link>
              <div v-html="matchParser(post.content)"></div>
            </div>
          </div>
        </div>
      </div>
    </PanelComponent>
  </Modal>
</template>
