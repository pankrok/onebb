import { PAGE_URL } from "@/helpers/api";
import useAxios from "./useAxios";
import { useRoute } from "vue-router";
import { instanceOf } from "./helpers";
import type { IHydra, IPage } from "@/interfaces";

export default function usePage() {
    const {axios} = useAxios();
    const route = useRoute();

    async function getPage() {
        const {data} = await axios.get<unknown>(`${PAGE_URL}/${route.params.id}`);
        if (instanceOf<IPage>(data))
            return data;

        return null;
    }

    async function getPages() {
        const {data} = await axios.get<unknown>(`${PAGE_URL}?page=1&limit=10`);
        if (instanceOf<IHydra<IPage>>(data))
            return data['hydra:member'];

        return [];
    }

    return {
        getPage,
        getPages,
    }
}