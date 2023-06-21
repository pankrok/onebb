<script setup lang="ts">
import { ref } from 'vue';
import Modal from './ModalComponent.vue';
import { useUser } from '@/hooks/useUser'
import BoxComponent from '@/components/box/BoxComponent.vue';
import type { ILoginCreditionals } from '@/interfaces/OnebbInterfaces'

const props = defineProps<{ loginToggle: Function }>()

const creditionals = ref<ILoginCreditionals>({
    username: '',
    password: '',
});
const msg = ref<{
    show: boolean,
    msg: string,
    class?: string[],
}>({
    show: false,
    msg: '',
    class: []
})
const { login } = useUser();

const auth = async () => {
    const isLogged = await login({
        username: creditionals.value.username,
        password: creditionals.value.password
    });

    if (isLogged) {
        msg.value.show = true;
        msg.value.msg = 'logged';
        msg.value.class = ['background-green', 'box-shadow-green'];
        setTimeout(() => {
            msg.value.show = false;
            props.loginToggle();
        }, 3000)
        return;
    }

    msg.value.show = true;
    msg.value.msg = 'Invalid creditionals !';
    msg.value.class = ['background-red', 'box-shadow-red'];

    setTimeout(() => {
        msg.value.show = false;
    }, 3000)
}

</script>
<template>
    <Modal>
        <BoxComponent :header="true">
            <template v-slot:header>
                <div class="row jutify-content-space-around align-items-center margin-m">
                    <div class="font-size-18 font-weight-600 col-11">
                        Login
                    </div>
                    <div class="col-1 cursor-pointer" @click="loginToggle()">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                            viewBox="0 0 384 512"><!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                            <path
                                d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" />
                        </svg>
                    </div>
                </div>
            </template>
            <div class="column padding-xl background-color-white">
                <Transition name="fade" mode="in-out">
                    <div class="position-relative" key="auth-box">
                        <div v-if="msg.show"
                            class="padding-m border-radius-5 text-align-center font-weight-600 color-light position-absolute"
                            :class="msg.class"
                            style="top: -45px;">
                            {{ msg.msg }}
                        </div>
                    </div>
                </Transition>
                <label class="label" for="username">Username</label>
                <input class="form-control" id="username" type="text" v-model="creditionals.username" />
                <label class="label" for="password">Password</label>
                <input class="form-control" id="password" type="password" v-model="creditionals.password" />
                <button class="button" @click="auth()">Login</button>
            </div>
        </BoxComponent>
    </Modal>
</template>