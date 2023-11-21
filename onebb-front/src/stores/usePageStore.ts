import usePage from '@/hooks/usePage';
import type { IPage } from '@/interfaces';
import {defineStore} from 'pinia';
import { ref } from 'vue';

const usePageStore = defineStore('pageStore', ()=>{
    const {getPages, getPage} = usePage()
    const pages = ref<IPage[]>([]);
    const page = ref<IPage|null>(null)
    
    async function initPages() {
        pages.value = await getPages();
    }

    async function fetchPage() {
        page.value = await getPage();
    }

    function $resetPage() {
        page.value = null;
    }

    return {pages, page, initPages, fetchPage, $resetPage}
})

export default usePageStore;