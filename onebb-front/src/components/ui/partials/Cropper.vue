<!-- eslint-disable vue/multi-word-component-names -->
<script setup lang="ts">
import { ref } from 'vue'
import { Cropper } from 'vue-advanced-cropper'
import 'vue-advanced-cropper/dist/style.css'
import type { IUser } from '@/interfaces'
import useAxios from '@/hooks/useAxios'
import useUserStore from '@/stores/useUserStore'
import { USER_URL } from '@/utils/apiRoutes'
import instanceOf from '@/utils/instanceOf'
import { $t } from '@/utils/i18n'
import useLoadingStore from '@/stores/useLoadingStore'

// const emit = defineEmits(['update'])
const emit = defineEmits<{
  (e: 'update', value: string): void
}>()
const userStore = useUserStore()
const { getUserId } = userStore
const loadingStore = useLoadingStore()
// import '../../assets/modal.css';
const image = ref()
const cropper = ref()
const saveBtn = ref(false)
//const coordinates = ref();
const uploadImage = (e: any) => {
  const reader = new FileReader()
  reader.readAsDataURL(e.target.files[0])
  reader.onload = (e: any) => {
    image.value = e.target.result
    saveBtn.value = true
  }
}

const change = async () => {
  saveBtn.value = false
  const { canvas } = cropper.value.getResult()

  const { axios } = useAxios()
  image.value = canvas.toDataURL()
  console.log(image.value)

  const { data } = await axios.put<unknown>(`${USER_URL}/${getUserId}/img`, {
    avatar: image.value
  })

  if (instanceOf<{ avatar: string }>(data)) {
    console.log({ data })
    emit('update', data.avatar)
  }

  saveBtn.value = true
}
</script>

<template>
  <div v-if="loadingStore.loading" class="row justify-content-center align-items-center col-9 height-9">
    <div class="lds-dual-ring"></div>
  </div>
  <div v-else>
    <div class="padding-m row">
      <span class="col-sm-11"> {{ $t('Profile picture') }} </span>
    </div>
    <div v-if="image" class="padding-l" style="width: 500px; height: 500px">
      <Cropper
        ref="cropper"
        :src="image"
        :transitions="true"
        default-boundaries="fill"
        class="cropper"
        :resizeImage="true"
        :stencil-size="{
          width: 300,
          height: 300
        }"
        image-class="cropper__image"
        :stencil-props="{
          aspectRatio: 1 / 1,
          resizable: false,
          class: 'cropper-stencil',
          previewClass: 'cropper-stencil__preview',
          draggingClass: 'cropper-stencil--dragging',
          handlersClasses: {
            default: 'cropper-handler',
            eastNorth: 'cropper-handler--east-north',
            westNorth: 'cropper-handler--west-north',
            eastSouth: 'cropper-handler--east-south',
            westSouth: 'cropper-handler--west-south'
          }
        }"
      />
    </div>
    <div class="row margin-m justify-content-space-between">
      <span class="button button-color-white margin-sm-x-l">
        <input
          id="userImg"
          class="btn-file"
          type="file"
          accept="image/jpeg"
          @change="uploadImage"
        />
        <label for="userImg">{{ $t('Choose image') }}</label>
      </span>
      <button
        v-if="saveBtn"
        class="button button-color-white margin-sm-x-l"
        @click="change()"
        type="button"
      >
        {{ $t('save') }}
      </button>
    </div>
  </div>
</template>

<style>
.vue-simple-handler {
  display: none !important;
}

.btn-file {
  position: relative;
  overflow: hidden;
}
.btn-file {
  position: absolute;
  top: 0;
  right: 0;
  min-width: 0;
  min-height: 0;
  font-size: 0;
  text-align: right;
  filter: alpha(opacity=0);
  opacity: 0;
  outline: none;
  background: white;
  cursor: inherit;
  display: block;
}
</style>
