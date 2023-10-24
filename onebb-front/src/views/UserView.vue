<script setup lang="ts">
import { useUser } from '@/hooks/useUser';
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import Box from '@/components/box/BoxComponent.vue';
import Image from '@/components/ui/ImageComponent.vue';
import Cropper from '@/components/ui/Cropper.vue';
import type { IUser } from '@/interfaces';

const route = useRoute();
const { getUserById, parseUsername, getUserId } = useUser();
const user = ref<IUser>();
const cropper = ref(false);
const change = () => {
    if (!user.value) {
        return;
    }

    if (user.value.id === getUserId()) {
        cropper.value = true;
    }
}

const update = (newAvatar: string) => {
    //window.location.reload();
    cropper.value = false;
    if (!user.value) {
        return;
    }

    user.value.avatar = newAvatar;
}

onMounted(async () => {
    const id = Number(route.params.id);
    const userdata = await getUserById(id);
    // @ts-ignore
    user.value = userdata?.parsedResponse ?? null
    console.log(user.value)
})



</script>

<template>
    <div v-if="user" class="row">
        <div class="col-3">
            <Box :wrapper-class="['margin-s']">
                <div class="column align-items-center margin-y-m">
                    <Cropper :is-open="cropper" @update="update"/>
                    <Image :size="[90, 90]" :src="user.avatar" :alt="user.username" :rounded="true" @click="change()" />
                    <div class="padding-m text-align-center font-size-16 margin-y-l" v-html="parseUsername(user)"></div>
                    <div class="col-12 row text-align-center padding-top-l">
                        <div class="col-sm-6">
                            Posts:
                        </div>
                        <div class="col-sm-6">
        
                            {{ user.posts_no }}
                        </div>
                        <div class="col-sm-6">
                            Plots:
                        </div>
                        <div class="col-sm-6">
                           
                            {{ user.plots_no }}
                        </div>
                    </div>
                </div>
            </Box>
        </div>
        <div class="col-9">
            <Box :wrapper-class="['margin-s']">
                [NOT IMPMEMENTED YET]
            </Box>
        </div>
    </div>
</template>