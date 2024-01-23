<!-- eslint-disable vue/multi-word-component-names -->
<script setup lang="ts">
import { ref } from 'vue'
import { Cropper } from 'vue-advanced-cropper'
import 'vue-advanced-cropper/dist/style.css'
import type { IUser } from '@/interfaces'
import useAxios from '@/hooks/useAxios'
import useUserStore from '@/stores/useUserStore'
import { USER_URL } from '@/utils/apiRoutes'
import instanceOf  from '@/utils/instanceOf'

defineProps<{
  isOpen: boolean
  handleClose?: Function
}>()

// const emit = defineEmits(['update'])
const emit = defineEmits<{
  (e: 'update', value: string): void
}>()
const userStore = useUserStore();
const {getUserId} = userStore;

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

  const {data} = await axios.put<unknown>(`${USER_URL}/${getUserId}/img`, {
    avatar: image.value
  })

  if (instanceOf<IUser>(data)) {
    emit('update', data.avatar)
  }

  saveBtn.value = true
}
</script>

<template>
  <Transition name="fade">
    <ModalComponent v-if="isOpen">
      <BoxComponent header footer :wrapper-class="['margin-s, col-9']">
        <template #header>
          <div class="padding-m row">
            <span class="col-sm-11"> Profile picture </span>
            <span v-if="handleClose" class="col-sm-1 cursor-pointer text-align-right" @click="handleClose()">
              <svg xmlns="http://www.w3.org/2000/svg" height="1em" fill="white" viewBox="0 0 384 512">
                <!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                <path
                  d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"
                ></path>
              </svg>
            </span>
          </div>
        </template>
        <div v-if="image" class="padding-l">
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
        <template #footer>
          <div class="row margin-m justify-content-space-between">
            <span class="button button-color-white border-radius-5">
              <input
                id="userImg"
                class="btn-file"
                type="file"
                accept="image/jpeg"
                @change="uploadImage"
              />
              <label for="userImg">Choose image</label>
            </span>
            <button
              v-if="saveBtn"
              class="button button-color-white"
              @click="change()"
              type="button"
            >
              {{ 'save' }}
            </button>
          </div>
        </template>
      </BoxComponent>
    </ModalComponent>
  </Transition>
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
