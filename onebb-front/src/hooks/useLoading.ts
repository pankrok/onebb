import { ref } from "vue";

const isLoading = ref(false);

export default function useLoading() {
    return {
        loading: () => {
            console.log({isLoading: true})
            isLoading.value = true;
        },

        loaded: ()=> {
            console.log({isLoading: false})
            isLoading.value = false;
        },

        isLoading
    }
}