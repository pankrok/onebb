<script setup lang="ts">
import { useRoute } from "vue-router";
import { useStore } from "vuex";
import { IUser } from "@/interfaces/obbApiInterface";
import { USER } from "@/services/api/obbResources";
import api from "@/services/api/api";
import { ref } from "vue";
import User from "@/components/ui/partial/User.vue";

const route = useRoute();
const store = useStore();
const userData = ref<IUser>();
const canEdit = ref(false);
const id: number = Number(route.params.id);

api.get<IUser>({ resource: USER, id: id }).then((response) => {
  if (response?.body) {
    userData.value = response.body;
    canEdit.value = (store.state.user.uid === userData.value.id);
    store.dispatch("setTitle", userData.value.username);
  }
})

</script>

<template>
    <div v-if="store.state.user.loggedIn">
       <User :canEdit="canEdit" :userData="userData" />
    </div>
</template>