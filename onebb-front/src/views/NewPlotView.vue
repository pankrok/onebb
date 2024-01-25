<script setup lang="ts">
import { $t } from '@/utils/i18n'
import { ref } from 'vue'
import { usePlot } from '@/hooks/obbClient'
import JoditComponent from '@/components/ui/partials/JoditComponent.vue'
import useUserStore from '@/stores/useUserStore'

const userStore = useUserStore()
const { createPlot } = usePlot()
const content = ref('')
const topic = ref('')
const error = ref(false);

async function createPlotWrapper() {
    if (content.value.length > 0 && topic.value.length > 0) {
        await createPlot({
            name: topic.value,
            content: content.value
        })

        return;
    }

    error.value = true;
}

</script>

<template>
  <div class="col-12 border-1 background-primary border-color-dark margin-y-l">
    <section class="row">
      <div class="col-12 padding-sm-m border-bottom-1 background-stripes border-color-dark">
        <h1 class="col-12-auto margin-sm-y-none">{{ $t('new plot') }}</h1>
      </div>
      <Transition mod="in-out" name="fade">
          <div
            v-if="error"
            class="border-1 col-12 text-align-center border-color-dark-red box-shadow-red background-red color-white font-size-14 font-weight-600 padding-sm-m margin-sm-y-m"
          >
            {{ $t('post or topic cannot be empty') }}
          </div>
        </Transition>
      <div  v-if="userStore.logged" class="col-12 padding-sm-s row">
        <label for="plot-name" class="col-12 color-white">{{ $t('topic') }}</label>
        <input id="plot-name" type="text" class="form-control col-12 margin-sm-y-m color-white" v-model="topic"/>
        <JoditComponent 
       
          :value="content"
          @update-event="
            (e) => {
                content = e
            }
          "
        
        />
        <div class="col-12 row justify-content-end">
            <button class="button button-color-green margin-sm-top-m" @click="createPlotWrapper">
                {{ $t('send') }}
            </button>
        </div>
      </div>
    </section>
  </div>
</template>
