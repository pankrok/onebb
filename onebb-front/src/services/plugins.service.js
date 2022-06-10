class Plugin {
   
    #store = null;
    
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
    
}

window.$obbPlugins = new Plugin();
export default window.$obbPlugins
