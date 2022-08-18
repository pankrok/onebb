<template>
<Transition name="fade">
<div v-if="pid" @click.self="$emit('update:pid', false)" class="modal">
    <Transition name="slide-fade" mode="out-in">
    <div v-if="error == true || success == true" class="alert column" :class="{ success: success, danger: error }">
        <div class="alert-body">
            <strong v-if="success"> {{ $t('plot save') }} </strong>
            <strong v-if="error"> {{ $t('something went wrong') }} </strong>
         </div>
    </div>
  <div v-else  class="box" style="width: 300px;">
    <div class="box-header text-center">
      <h4>{{ $t('edit plot') }}</h4>
    </div>
   
    <div class="box-content">
        <span v-if="saving" class="box-loader"></span>

        <div class="col-12 column my-2">
          <label for="plotName">{{ $t('plot name') }}</label>
          <input v-model="plot.name" type="text" name="plotName" id="plotName" class="form-control" :class="{ 'form-control-disabled': saving }" :disabled="saving">
        </div>
        <div class="col-12 row j-c-space-between my-2">
          <label for="switch-hidden">{{ $t('hidden') }}</label>
          <div class="onoffswitch mx-2">
            <input type="checkbox" name="hidden" class="onoffswitch-checkbox" id="switch-hidden" tabindex="0" v-model="plot.hidden">
            <label class="onoffswitch-label" for="switch-hidden"></label>
          </div>
        </div>
        <div class="col-12 row j-c-space-between my-2">
          <label for="switch-locked">{{ $t('locked') }}</label>
          <div class="onoffswitch mx-2">
            <input type="checkbox" name="locked" class="onoffswitch-checkbox" id="switch-locked" tabindex="0" v-model="plot.locked">
            <label class="onoffswitch-label" for="switch-locked"></label>
          </div>
        </div>
        <div class="col-12 column my-2">
          <button class="btn btn-success" @click="plotSave">{{ $t('save') }}</button>
        </div>

    </div>
    
  </div>
  </Transition>
</div>
</Transition>
</template>

<script>
export default {
  name: 'PlotMod',
  props: {
    pid: Number,
  },
  data() {
    return {
        plot: {
            name: '',
            hidden: false,
            locked: false,
        },
        saving: false,
        error: false,
        success: false,
    }
  },
  methods: {  
    plotSave() {
        this.saving = true;
        this.$store.dispatch('onebb/put', {
            resource: 'plot',
            id: this.pid,
            data: this.plot,
        }).then(() => {
           this.saving = false;
           this.success = true;
           setTimeout(() => {
               this.success = false;
               this.$emit('update:pid', 0);
            }, 2000)
        });
    }
  },
  mounted() {
    this.$store.dispatch('onebb/get', {
            resource: 'plot',
            id: this.$route.params.id,
        }).then(response => {
           this.plot = {
                name: response.name,
                hidden: response.hidden,
                locked: response.locked,
            }
        });
  },
  emits: ['update:pid']
}
</script>
<style scoped>
   @import '../../../assets/modal.css';
</style>
