import useApi from "./useApi"

const useCategory = async (id?: string|string[]|number) => {
    const api = useApi()
    const endpoint = 'categories/' + (id ?? '');
    const response = await api.get(endpoint);
    
    return response
}

export default useCategory;