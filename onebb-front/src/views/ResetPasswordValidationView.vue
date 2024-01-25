<script setup lang="ts">
import { useUser } from '@/hooks/obbClient';
import { $t } from '@/utils/i18n';
import { ref } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const success = ref(false);
const error = ref<string|null>(null);
const password = ref('');
const vpassword = ref('');
const {resetPassword} = useUser();

async function resetPasswordWrapper() {
    
    if (password.value.length < 1) {
        error.value = 'password cannot be empty'
        return;
    }

    if (password.value !== vpassword.value) {
        error.value = 'passwords not match'
        return;
    }

    error.value = null;
    await resetPassword(password.value, String(route.params.hash))
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
            {{ $t('Password changed') }}
          </div>
        </Transition>
        <Transition mod="in-out" name="fade">
          <div
            v-if="error"
            class="border-1 col-12 text-align-center border-color-dark-red box-shadow-red background-red color-white font-size-14 font-weight-600 padding-sm-m margin-sm-y-m"
          >
            {{ $t(error) }}
          </div>
        </Transition>
        <div
              class="col-12 padding-sm-m border-bottom-1 background-stripes border-color-dark"
            >
      <h1 class="col-12-auto margin-sm-y-none">{{ $t('Forget password') }}</h1>
      </div>
     <div class="col-12 row padding-sm-s justify-content-center">
        <label for="password">{{ $t('Password') }}</label>
        <div class="col-12 row  justify-content-center">
          <input id="password" type="password" class="form-control margin-sm-y-m color-white" v-model="password" />
        </div>
        <label for="vpassword">{{ $t('Confirm Password') }}</label>
        <div class="col-12 row  justify-content-center">
          <input id="vpassword" type="password" class="form-control margin-sm-y-m color-white" v-model="vpassword" />
        </div>
        
        <button type="button" class="button button-color-white color-white margin-sm-top-m" @click="resetPasswordWrapper">
            {{ $t('send') }}
        </button> 

     </div>
    </section>
    </div>
</template>