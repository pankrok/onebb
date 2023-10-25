<script setup lang="ts">
import BoxComponent from '@/components/box/BoxComponent.vue'
import InputComponentVue from '@/components/ui/InputComponent.vue'
import { useUser } from '@/hooks/useUser'
import useApi from '@/hooks/useApi'
import { reactive } from 'vue'
import { useToast } from '@/hooks/useToast'

const { getUserId } = useUser()
const { put } = useApi()
const { setAlert } = useToast()

const userData = reactive<{
  password: string
  passwordError: string | null
  vPassword: string
  email: string,
}>({
  password: '',
  passwordError: null,
  vPassword: '',
  email: '',
})

async function updateUserData() {
  if (userData.password.length < 8 && userData.password.length !== 0) {
    userData.passwordError = 'Password must be at least 8 characters long'
    return
  }

  if (userData.password !== userData.vPassword) {
    userData.passwordError = 'Passwords does not match'
    return
  }

  userData.passwordError = null
  const payload: {password?: string, email?: string} = {};
  if (userData.password.length > 1) {
    payload.password = userData.password;
  }

  if (userData.password.length > 1) {
    payload.password = userData.password;
  }

  if (userData.email.length > 1) {
    payload.email = userData.email;
  }

  try {
    await put(`users/${getUserId()}`, {
        payload
    })
  } catch (e) {
    setAlert({
      name: 'User update',
      text: 'something went wrong, try again later!',
      type: 'alert'
    }, 3000)
  } finally {
    setAlert({
      name: 'User update',
      text: 'Your userdata is update',
      type: 'success'
    }, 3000)
  }
}
</script>

<template>
  <BoxComponent>
    <InputComponentVue
      :id="'password'"
      label="password"
      v-model="userData.password"
      :error="userData.passwordError"
    />
    <InputComponentVue :id="'vpassword'" label="Validate Password" v-model="userData.vPassword" />
    <InputComponentVue :id="'email'" label="Email" v-model="userData.email" />
    <div class="col-12 row justify-content-flex-end margin-top-m">
      <button class="button button-color-blue" @click="updateUserData">Update Password</button>
    </div>
  </BoxComponent>
</template>
