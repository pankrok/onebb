<script setup lang="ts">
import { ref } from 'vue';
import Modal from './ModalComponent.vue';
import { useUser } from '@/hooks/useUser'
import BoxComponent from '@/components/box/BoxComponent.vue';
import type { ILoginCreditionals } from '@/interfaces'
import { useToast } from '@/hooks/useToast';
import useAuth from '@/hooks/useAuth';

const {setAlert} = useToast();

const props = defineProps<{ loginToggle: Function }>()

const creditionals = ref<ILoginCreditionals>({
    username: '',
    password: '',
});

const { login } = useUser();

const auth = async () => {
    const {signIn} = useAuth();
    const res = await signIn(creditionals.value);
    console.log({res})
    // const isLogged = await login({
    //     username: creditionals.value.username,
    //     password: creditionals.value.password
    // });

    // if (isLogged) {
    //     props.loginToggle();
    //     setAlert({
    //         name: 'Auth',
    //         text: 'You are successful logged in!',
    //         type: 'success'
    //     });
    //     return;
    // }

    // setAlert({
    //         name: 'Auth',
    //         text: 'Invalid creditionals !',
    //         type: 'warning'
    // });
}

</script>
<template>
    <Modal>
        <BoxComponent :header="true">
            <template v-slot:header>
                <div class="row jutify-content-space-around align-items-center margin-m">
                    <div class="font-size-18 font-weight-600 col-sm-11">
                        Login
                    </div>
                    <div class="col-sm-1 cursor-pointer" @click="loginToggle()">
                        <svg fill="white" xmlns="http://www.w3.org/2000/svg" height="1em"
                            viewBox="0 0 384 512"><!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                            <path
                                d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" />
                        </svg>
                    </div>
                </div>
            </template>
            <div class="column padding-xl background-color-white">
                <label class="label" for="username">Username</label>
                <input class="form-control color-white" id="username" type="text" v-model="creditionals.username" />
                <label class="label" for="password">Password</label>
                <input class="form-control color-white" id="password" type="password" v-model="creditionals.password" />
                <button class="button color-white margin-top-l" @click="auth()">Login</button>
            </div>
        </BoxComponent>
    </Modal>
</template> 