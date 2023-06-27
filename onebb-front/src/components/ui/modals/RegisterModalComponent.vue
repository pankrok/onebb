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

const errors = reactive({
    username: '',
    password: '',
    email: '',
    vpassword: ''
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
const vpassword = ref('')
const signUp = async () => {
    if (vpassword.value !== creditionals.password) {
        errors.password = errors.vpassword = 'Passwords does not match!';
        return;
    }

    const isRegister = await register(creditionals);
    // @ts-ignore
    if (typeof isRegister.parsedResponse.violations === 'undefined') {
        msg.show = true;
        msg.msg = 'logged';
        msg.class = ['background-green', 'box-shadow-green'];
        setTimeout(() => {
            msg.show = false;
            props.toggleModal();
        }, 3000)
        return;
    }

    // @ts-ignore
    isRegister.parsedResponse.violations.forEach(err => {
        // @ts-ignore
        errors[err.propertyPath] = err.message;
    })

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
        <BoxComponent :wrapperClass="['minWidth-50wv']" :header="true">
            <template v-slot:header>
                <div class="row jutify-content-space-around align-items-center margin-m">
                    <div class="font-size-18 font-weight-600 col-11">
                        Register
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
            <div class="column padding-xl background-color-white" style="min-width: 50wv;">
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
                <input class="form-control" :class="[errors.username.length > 0 ? 'border-color-red' : '']" id="username" type="text" v-model="creditionals.username" />
                <span class="color-red font-size-12 margin-x-m">{{ errors.username }}</span>
                <label class="label" :class="[errors.email.length > 0 ? 'border-color-red' : '']" for="Email">Email</label>
                <input class="form-control" id="Email" type="text" v-model="creditionals.email" />
                <span class="color-red font-size-12 margin-x-m">{{ errors.email }}</span>
                <label class="label" for="password">Password</label>
                <input class="form-control" :class="[errors.password.length > 0 ? 'border-color-red' : '']" id="password" type="password" v-model="creditionals.password" />
                <span class="color-red font-size-12 margin-x-m">{{ errors.password }}</span>
                <label class="label" for="vpassword">Confirm password</label>
                <input class="form-control" :class="[errors.vpassword.length > 0 ? 'border-color-red' : '']" id="vpassword" type="password" v-model="vpassword" />
                <span class="color-red font-size-12 margin-x-m">{{ errors.vpassword }}</span>
                <button class="button" @click="signUp()">Sign up</button>
            </div>
        </BoxComponent>
    </Modal>
</template>