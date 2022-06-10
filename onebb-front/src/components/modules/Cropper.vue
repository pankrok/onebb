<template>
<Transition name="fade">
<div v-if="show" @click.self="$emit('update:show', false)" class="modal column">
<div class="box">
   <cropper v-if="image"
        ref="cropper"
      :src="image"
      :transitions="true"
      default-boundaries="fill"
      class="cropper"
      resizeImage="fales"
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
<div class="box">
    <div class="box-content p-4">
        <span class="btn btn-secondary btn-file mx-2">
            {{ $t('Browse...') }}<input  type="file" accept="image/jpeg" @change=uploadImage>
        </span>
        <button v-if="image" class="btn btn-secondary mx-2" @click="change" type="button">{{ $t('save') }}</button>
    </div>
</div>
</div>
</Transition>
</template>

<script>
import { Cropper } from "vue-advanced-cropper";
import 'vue-advanced-cropper/dist/style.css';
import '../../assets/modal.css';

export default {
  name: "Croppie",
  data() {
    return {
      image: null,
      coordinates: null
    };
  }, 
  components: {
    Cropper,
  },
  props: {
    show: Boolean,
  },
  methods: {
    
    change() {
        const { coordinates, canvas, } = this.$refs.cropper.getResult();
            this.coordinates = coordinates;

			this.image = canvas.toDataURL();
            this.$store.dispatch(
                'onebb/put', 
                {
                    resource: 'user',
                    id: this.$store.state.onebb.status.uid + '/img',
                    data: {
                        avatar: this.image
                    }
                }
            ).then(response => {
                this.$store.dispatch('onebb/newAvatar', response.avatar);
                this.$emit('update:show', false);
            });
            
            
    },
    uploadImage(e){
        const image = e.target.files[0];
        const reader = new FileReader();
        reader.readAsDataURL(image);
        reader.onload = e =>{
            this.image = e.target.result;
            console.log(this.image);
        };
    }
  },
  emits: ['update:show']
}
</script>
<style>
    .vue-simple-handler {
      display: none !important;
    }
    
    .btn-file {
      position: relative;
      overflow: hidden;
    }
    .btn-file input[type=file] {
      position: absolute;
      top: 0;
      right: 0;
      min-width: 100%;
      min-height: 100%;
      font-size: 100px;
      text-align: right;
      filter: alpha(opacity=0);
      opacity: 0;
      outline: none;
      background: white;
      cursor: inherit;
      display: block;
    }
</style>
