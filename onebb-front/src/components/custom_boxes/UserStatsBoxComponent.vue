<script setup lang="ts">
import type {IUser} from '../../interfaces'
import { useUser } from '../../hooks/useUser';
import { ref, watch } from 'vue';

const {parseUsername, getUserById, getUserId} = useUser();
const id = getUserId()
const userData = ref<IUser|null>(null)

// watch(id, async (currentId)=>{
//     if(currentId) {
//         // @ts-ignore
//         const {parseResponse} = await getUserById(Number(currentId));
//         userData.value = parseResponse;
//         return;
//     }

//     userData.value = null;
//     return;
// });

</script>

<template>
    <h2>
        userdata: {{ userData }}
    </h2>
</template>
