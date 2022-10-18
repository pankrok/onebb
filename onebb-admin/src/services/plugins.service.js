let cfg = document.getElementById('app').dataset;
const API_URL = cfg.url + cfg.obb + 'api/';

class Plugin {
   
    #store = null;
    #router = null
    #request = null;
    
    constructor() {
        this.config = {
            method: 'POST',
            credentials: 'include',
            mode: 'cors',
            cache: 'no-cache',
            redirect: 'follow',
            referrerPolicy: 'no-referrer',
            headers: {
                'Content-Type': 'application/ld+json',
                'Accept': 'application/ld+json',
                'X-ONEBB-ADMIN': 'true'
            },
            body: null,
        };
        
        this.#request = async function() {
            
            let response = null;
            
            const req = await fetch(API_URL + 'plugin/dispatch', this.config);
            response =  req.json();
          
            this.config.body = null;
            return {status: req.status, response: response};
        };
        
       
    }
    
    setRouter(router) {
        if (this.#router === null) {
            this.#router = router;
            return this;
        }
        
        throw 'Router is already set!';
    }
    
    setStore(store) {
        if (this.#store === null) {
            this.#store = store;
            return this;
        }
        
        throw 'Store is already set!';
    }
    
    routerPluginPush(plugin, temp = null, script = null, q = {},) {
        this.#router.push({ 
            name: 'PluginControl', 
            params: { 
                plugin: plugin, 
                temp: temp, 
                script: script
            }, 
            query: q
        });
    }
    
    getParam(p) {
        const params = new Proxy(new URLSearchParams(window.location.search), {
          get: (searchParams, prop) => searchParams.get(prop),
        });
        
        return params[p] ?? null;
    }
       
    serializeForm(form) {
        let obj = {};
        let formData = new FormData(form);
        for (let key of formData.keys()) {
            obj[key] = formData.get(key);
        }
        return obj;
    }
       
    dispatch(plugin, event, data = null) {
        this.config.body = JSON.stringify({
            plugin: plugin,
            event: event,
            data: data
        });
        
        
        return new Promise((resolve) => {

            this.#store.dispatch('onebb/getToken').then(token => {
                this.config.headers.Authorization = 'Bearer ' + token;
                this.#request().then(ret => {
                    if (ret.status == 200) {
                        ret.response.then(data => { 
                            resolve({
                                success: true,
                                data: data,
                            });
                        });
                    }
                });
            })
        })
    }

}

window.$obbPlugins = new Plugin();
export default window.$obbPlugins
