import useApi from "./useApi"

const useCategory = async (id?: string|string[]|number) => {
    const api = useApi()
    const endpoint = 'categories' + (id ? `/${id}` : '');
    const {response, parsedResponse} = await api.get(endpoint);
    return parsedResponse ?? response
}

export default useCategory;