<script setup lang="ts">
import { defineProps } from 'vue';
import { useI18n } from 'vue-i18n';
import {ISignIn } from '@/interfaces/FormInterfaces';

const { t } = useI18n();
const form: ISignIn = {
    username: '',
    password: '',
}

const props = defineProps<{
    callback: Function,
    hideModal: Function,
    logging: boolean,
    error: boolean,
    success: boolean
}>();



</script>
<template>
    <Transition name="slide-fade" mode="out-in">
    <div v-if="props.error == true || props.success == true" class="alert column" :class="{ success: props.success, danger: props.error }">
        <div class="alert-body">
            <strong v-if="props.success"> {{ t('you are logged in') }} </strong>
            <strong v-if="props.error"> {{ t('invalid email or password') }} </strong>
         </div>
    </div>
  <div v-else  class="box" style="width: 300px;">
    <div class="box-header text-center">
      <h4>{{ t('sign in') }}</h4>
    </div>
   
    <div class="box-content">
        <span v-if="logging" class="box-loader"></span>
        <div class="col-12 column">
          <label for="exampleInputEmail1">{{ t('Login') }}</label>
          <input v-model="form.username" type="text" name="login" id="login" class="form-control" :class="{ 'form-control-disabled': logging }" :disabled="logging">

        </div>
        <div class="col-12 column">
          <label for="exampleInputPassword1">{{ t('Password') }}</label>
          <input v-model="form.password" type="password" name="password" id="password" class="form-control" :class="{ 'form-control-disabled': logging }" :disabled="logging">
        </div>
        <div class="col column my-1">
          <button :disabled="logging" :class="{ 'btn-disabled': logging }" @click="callback(form)" type="submit" class="btn btn-secondary">{{ t('sign in') }}</button>
        </div>
        <hr />
        <div class="col column my-1 text-center">
        <!--
          <router-link  @click="props.hideModal()"
            :to="{ name: 'ResetPassword' }"
           >
                <small>{{ t('Forget password') }}</small>
           </router-link>-->
        </div>
    </div>
    
  </div>
  </Transition>
</template>