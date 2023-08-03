<script setup lang="ts">
import { ref } from "vue";
import { Cropper } from "vue-advanced-cropper";
import BoxComponent from "../box/BoxComponent.vue";
import ModalComponent from "./modals/ModalComponent.vue";
import 'vue-advanced-cropper/dist/style.css';
import useApi from "@/hooks/useApi";
import { useUser } from "@/hooks/useUser";
import type { IUser } from "@/interfaces/OnebbInterfaces";

defineProps<{
    isOpen: boolean
}>()

// const emit = defineEmits(['update'])
const emit = defineEmits<{
  (e: 'update', value: string): void
}>();

const uid = useUser().getUserId();
// import '../../assets/modal.css';
const image = ref()
const cropper = ref()
const saveBtn = ref(false)
//const coordinates = ref();
const  uploadImage = (e: any) => {
    const reader = new FileReader();
    reader.readAsDataURL(e.target.files[0]);
    reader.onload =  (e: any) =>{
        image.value = e.target.result
        saveBtn.value = true;
    };
}
    
const change = async () => {
    saveBtn.value = false;
    const { canvas } = cropper.value.getResult();

    const {put} = useApi();
    image.value = canvas.toDataURL();
    console.log(image.value)

    const response = await put<IUser>(`users/${uid}/img`, {
        avatar: image.value
    })

    if(response.parsedResponse)
        emit('update', response.parsedResponse.avatar);

    saveBtn.value = true;
}
    </script>

<template>
    <Transition name="fade">
    <ModalComponent v-if="isOpen">
        <BoxComponent header footer :wrapper-class="['margin-s']">
            <template #header>
                <div class="padding-m">
                    Profile picture
                </div>
            </template>
            <div class="padding-l">
                <Cropper v-if="image"
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
                        aspectRatio: 1/1,
                        resizable: false,
                        class: 'cropper-stencil',
                        previewClass: 'cropper-stencil__preview',
                        draggingClass: 'cropper-stencil--dragging',
                        handlersClasses: {
                        default: 'cropper-handler',
                        eastNorth: 'cropper-handler--east-north',
                        westNorth: 'cropper-handler--west-north',
                        eastSouth: 'cropper-handler--east-south',
                        westSouth: 'cropper-handler--west-south',
                        },
                    }"
                />
        </div>
        <template #footer>
            <div class="row margin-m justify-content-space-between">
                <span class="button button-background-blue button-color-white border-radius-5">
                    <input id="userImg" class="btn-file" type="file" accept="image/jpeg" @change="uploadImage" />
                    <label for="userImg">Choose image</label>
                </span>
                <button v-if="saveBtn" class="button button-background-blue button-color-white border-radius-5" @click="change()" type="button">{{ 'save' }}</button>
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
    