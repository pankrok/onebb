const useApi = () => {
    const settings = {
        // TODO: url should be dynamic
        url: 'https://bdev.s89.eu/api/',
    }
    
    const options = {
        headers: {
            "Content-Type": "application/ld+json",
        }
    }

    const get = async <T>(endpoint: string, query?: string):Promise<T> => {
        const url = settings.url + endpoint + (query ? query : '');
        const response = await fetch(url, options);
        const jsonData:Promise<T> = await response.json();
        return jsonData;
    }

    const post = async <T, R>(endpoint: string, body: R):Promise<T> => {
        const url = settings.url + endpoint;
        const response = await fetch(url, {method: 'POST',  body: JSON.stringify(body), ...options});
        const jsonData:Promise<T> = await response.json();
        return jsonData;
    }

    return {
        get,
        post,
        // put,
        // delete,
    }
}
export default useApi