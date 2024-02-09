<script setup lang="ts">
import { useRouter } from 'vue-router'
import { $t } from '@/utils/i18n'
import useAlertStore from '@/stores/useAlertStore'
import { defineAsyncComponent, ref } from 'vue'
import useAxios from '@/hooks/useAxios'
import useLoadingStore from '@/stores/useLoadingStore'
import { storeToRefs } from 'pinia'

interface ISignUpModal {
  registerModal: boolean
  setRegisterModal: (payload: boolean) => {}
}
const ModalComponent = defineAsyncComponent(
  () => import('@/components/ui/elements/ModalComponent.vue')
)
const { loading } = storeToRefs(useLoadingStore())
const { signUp } = useAxios()
const { setRegisterModal } = defineProps<ISignUpModal>()
const router = useRouter()
const alertStore = useAlertStore()

const registerData = ref({
  username: '',
  password: '',
  vpassword: '',
  email: ''
})

async function registerWrapper() {
  function alert(message: string, type: 'alert-danger' | 'alert-success' = 'alert-danger') {
    alertStore.setAlert({
      type,
      message,
      timeout: 5
    })
  }

  if (registerData.value.password !== registerData.value.vpassword) {
    alert($t('passwords not match'))
    return
  }

  if (registerData.value.password.length < 4 || registerData.value.password.length > 32) {
    alert($t('Password must be bettwen 4 and and 32 characters'))
    return
  }

  if (registerData.value.username.length < 4 || registerData.value.username.length > 32) {
    alert($t('Username must be bettwen 4 and and 32 characters'))
    return
  }

  if (registerData.value.email.length < 1) {
    alert($t('Email cannot be empty'))
    return
  }

  try {
    await signUp(registerData.value)
    alert($t('Congratulations, your account has been successfully created'), 'alert-success')
    setTimeout(() => {
      setRegisterModal(false)
    }, 3000)
  } catch (e) {
    alert('Something went wrong, please contact with the administrator.')
  }
}
</script>

<template>
  <ModalComponent :is-active="registerModal" :on-close="()=>setRegisterModal(false)" key="register-modal">
    <h3 class="text-align-center margin-sm-top-s">{{ $t('sign up') }}</h3>

    <label for="username" class="font-size-14">{{ $t('username') }}</label>
    <input
      id="username"
      v-model="registerData.username"
      type="text"
      class="form-control color-white"
    />
    <label for="email" class="font-size-14 margin-sm-top-m">{{ $t('Email') }}</label>
    <input id="email" v-model="registerData.email" type="mail" class="form-control color-white" />
    <label for="password" class="font-size-14 margin-sm-top-m">{{ $t('Password') }}</label>
    <input
      id="password"
      v-model="registerData.password"
      type="password"
      class="form-control color-white"
    />
    <label for="vpassword" class="font-size-14 margin-sm-top-m">{{ $t('Confirm Password') }}</label>
    <input
      id="vpassword"
      v-model="registerData.vpassword"
      type="password"
      class="form-control color-white"
    />

    <button
      type="button"
      @click="registerWrapper"
      class="button color-white margin-sm-top-m"
      :class="{ 'button-disabled': loading }"
      :disabled="loading"
    >
      {{ $t('sign up') }}
    </button>
  </ModalComponent>
</template>
