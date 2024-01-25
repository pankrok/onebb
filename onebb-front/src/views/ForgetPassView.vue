<script setup lang="ts">
import { useUser } from '@/hooks/obbClient';
import { $t } from '@/utils/i18n';
import { ref } from 'vue';


const success = ref(false);
const email = ref('');
const {forgetPassword} = useUser();

async function forgetPasswordWrapper() {
    await forgetPassword(email.value);
    success.value = true;
}

</script>

<template>
    <div class="col-12 border-1 background-primary border-color-dark margin-y-l">
    <section class="row">
        <Transition mod="in-out" name="fade">
          <div
            v-if="success"
            class="border-1 col-12 text-align-center border-color-dark-green box-shadow-green background-green color-white font-size-14 font-weight-600 padding-sm-m margin-sm-y-m"
          >
            {{ $t('') }}
          </div>
        </Transition>
        <div
              class="col-12 padding-sm-m border-bottom-1 background-stripes border-color-dark"
            >
      <h1 class="col-12-auto margin-sm-y-none">{{ $t('forget password') }}</h1>
      </div>
     <div class="col-12 row padding-sm-s justify-content-center">
        <label for="forget-mail">{{ $t('enter your email address') }}</label>
        <div class="col-12 row  justify-content-center">
          <input id="forget-mail" type="mail" class="form-control margin-sm-y-m color-white" v-model="email" />
        </div>
        
        <button type="button" class="button button-color-white color-white margin-sm-top-m" @click="forgetPasswordWrapper">
            {{ $t('send') }}
        </button> 

     </div>
    </section>
    </div>
</template>