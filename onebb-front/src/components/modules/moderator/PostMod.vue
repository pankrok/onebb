<template>
<Transition name="fade">
<div v-if="pid" @click.self="$emit('update:pid', 0)" class="modal">
    <Transition name="slide-fade" mode="out-in">
    <div v-if="error == true || success == true || pid == 0" class="alert column" :class="{ success: success, danger: error }">
        <div class="alert-body">
            <strong v-if="success"> {{ $t('post save') }} </strong>
            <strong v-if="error"> {{ $t('something went wrong') }} </strong>
         </div>
    </div>
  <div v-else class="box">
    <div class="box-header text-center">
      <h4>{{ $t('edit post') }}</h4>
    </div>
   
    <div class="box-content">
        <span v-if="logging" class="box-loader"></span>

        <div class="col-12 column my-2">
          <Jodit v-model:value="content" :config="jodit_cfg" />
        </div>
        <div class="col-12 row j-c-center my-2">
          <label for="switch-hidden">{{ $t('hidden') }}</label>
          <div class="onoffswitch mx-2">
            <input v-model="hidden" type="checkbox" name="hidden" class="onoffswitch-checkbox" id="switch-hidden" tabindex="0">
            <label class="onoffswitch-label" for="switch-hidden"></label>
          </div>
        </div>
         <div class="col-12 column my-2">
          <button class="btn btn-success" @click="postSave">{{ $t('save') }}</button>
        </div>
        
        </div>

    </div>
  </Transition>
</div>
</Transition>
</template>

<script>
import { defineAsyncComponent } from 'vue'
export default {
  name: 'PostMod',
  props: {
    pid: Number,
  },
  data() {
    return {
        content: '',
        jodit_cfg: {},
        hidden: null,
        logging: false,
        error: false,
        success: false,
    }
  },
  watch: {
    pid(newPid) {
        if(newPid !== 0)
            this.getContent(newPid);
    },
  },
  methods: {  
    async getContent(pid) {       
        this.$store.dispatch('onebb/get', {
            resource: 'posts',
            id: pid,
        }).then(response => {
           this.content = response.content;
        });
    },
    postSave(){
        console.log({
            content: this.content,
            hidden: this.hidden
        });
    }
  },
  components: {
    Jodit: defineAsyncComponent(() =>
        import('./../JoditEditor')
    ),
  },  
  emits: ['update:pid']
}
</script>
<style scoped>
   @import '../../../assets/modal.css';
</style>
