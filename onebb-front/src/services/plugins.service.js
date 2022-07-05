let cfg = document.getElementById('app').dataset;
const API_URL = cfg.url + cfg.obb + 'api/';

class Plugin {
   
    #store = null;
    #request = null;
    #events = {
        Home: [],
        SignUp: []
    };
    
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
                'Accept': 'application/ld+json'
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
        
        this.context = function(name) {
            if (typeof(this.#events[name]) === 'undefined') {
                return null;
            }
            
            if (this.#events[name].length === 0) {
                return null;
            }
            
            this.#events[name].forEach((el) => {
                el();   
            })
        };
    }
    
    
    subscribe(context, func) {
        this.#events[context].push(func);
    }
    
    setStore(store) {
        if (this.#store === null) {
            this.#store = store;
            return this;
        }
        
        throw 'Store is already set!';
    }
    
    add(plugin) {
        this.#store.dispatch('plugins/addPlugin', plugin)
    }
    
    reload() {
        this.#store.dispatch('plugins/reloadPlugins');
    }
    
    hello() {
         console.log('%cPlugin Service say: %cHello!!', 'color: #9B3939;', 'color: green;');
    }
    
    dispatch(plugin, event) {
        this.config.body = JSON.stringify({
            plugin: plugin,
            event: event
        });
        
        
        return new Promise((resolve) => {
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
    }

}

window.$obbPlugins = new Plugin();
export default window.$obbPlugins
