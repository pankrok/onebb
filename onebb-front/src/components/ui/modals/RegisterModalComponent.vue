<script setup lang="ts">
import { reactive, ref } from 'vue';
import Modal from './ModalComponent.vue';
import { useUser } from '@/hooks/useUser'
import BoxComponent from '@/components/box/BoxComponent.vue';
import type { IRegister } from '@/interfaces/OnebbInterfaces'

const props = defineProps<{ toggleModal: Function }>()

const creditionals = reactive<IRegister>({
    username: '',
    password: '',
    email: ''
});
const msg = reactive<{
    show: boolean,
    msg: string,
    class?: string[],
}>({
    show: false,
    msg: '',
    class: []
})
const { register } = useUser();

const signUp = async () => {
    const isRegister = await register(creditionals);
    
    if (typeof isRegister?.parsedResponse.violations === undefined) {
        msg.show = true;
        msg.msg = 'logged';
        msg.class = ['background-green', 'box-shadow-green'];
        setTimeout(() => {
            msg.show = false;
            props.toggleModal();
        }, 3000)
        return;
    }

    msg.show = true;
    msg.msg = 'Invalid creditionals!';
    msg.class = ['background-red', 'box-shadow-red'];

    setTimeout(() => {
        msg.show = false;
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
                    <div class="col-1 cursor-pointer" @click="toggleModal()">
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
                    <div class="position-relative" key="signUp-box">
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
                <label class="label" for="Email">Email</label>
                <input class="form-control" id="Email" type="text" v-model="creditionals.email" />
                <label class="label" for="password">Password</label>
                <input class="form-control" id="password" type="password" v-model="creditionals.password" />
                <button class="button" @click="signUp()">Sign up</button>
            </div>
        </BoxComponent>
    </Modal>
</template>