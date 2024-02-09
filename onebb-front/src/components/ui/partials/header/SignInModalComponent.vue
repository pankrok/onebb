<script setup lang="ts">
import { useRouter } from 'vue-router'
import { $t } from '@/utils/i18n'
import useAlertStore from '@/stores/useAlertStore'
import { defineAsyncComponent, ref } from 'vue'
import useAxios from '@/hooks/useAxios'
import useLoadingStore from '@/stores/useLoadingStore'
import { storeToRefs } from 'pinia'

interface ISignInModal {
  loginModal: boolean
  setLoginModal: (payload: boolean) => {}
}
const ModalComponent = defineAsyncComponent(
  () => import('@/components/ui/elements/ModalComponent.vue')
)
const { loading } = storeToRefs(useLoadingStore())
const { signIn } = useAxios()
const { setLoginModal } = defineProps<ISignInModal>()
const router = useRouter()
const alertStore = useAlertStore()

const creditionals = ref({
  username: '',
  password: ''
})

function forgetMyPass() {
  setLoginModal(false)
  router.push({ name: 'ForgetPassword' })
}

async function signInWrapper() {
  try {
    const loginResponse = await signIn(creditionals.value)
    if (loginResponse) {
      setLoginModal(false)
      alertStore.setAlert({
        type: 'alert-success',
        message: $t('you are logged sucessful'),
        timeout: 5
      })
    }
  } catch (e: any) {
    alertStore.setAlert({
      type: 'alert-danger',
      message: $t(e.response.data.message),
      timeout: 5
    })
    console.log({ e })
  }
}
</script>

<template>
    <ModalComponent
      :is-active="loginModal"
      :on-close="
        () => {
          setLoginModal(false)
        }
      "
      key="login-modal"
    >
      <label for="username" class="font-size-14">{{ $t('username') }}</label>
      <input
        id="username"
        v-model="creditionals.username"
        type="text"
        class="form-control color-white"
        v-on:keyup.enter="signInWrapper"
      />
      <label for="password" class="font-size-14 margin-sm-top-m">{{ $t('Password') }}</label>
      <input
        id="password"
        v-model="creditionals.password"
        type="password"
        class="form-control color-white"
        v-on:keyup.enter="signInWrapper"
      />

      <button
        type="button"
        @click="()=>signInWrapper()"
        class="button color-white margin-sm-top-m"
        :class="{ 'button-disabled': loading }"
        :disabled="loading"
      >
        {{ $t('sign in') }}
      </button>

      <div class="col-12 borde-top-1 border-color-light row-sm justify-sm-content-center">
        <button
          type="button"
          @click="forgetMyPass"
          class="button button-color-yellow color-white margin-sm-top-l"
        >
          {{ $t('forget password') }}
        </button>
      </div>
    </ModalComponent>
</template>
