import { ref } from "vue";

const isLoading = ref<boolean>(false);

export default function useLoading() {
    return {
        loading: () => {
            isLoading.value = true;
        },

        loaded: ()=> {
            isLoading.value = false;
        },

        isLoading
    }
}