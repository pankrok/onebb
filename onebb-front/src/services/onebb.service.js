let cfg = document.getElementById('app').dataset;
const API_URL = cfg.url + cfg.obb + 'api/';

class OneBB {
  constructor() {
        this.defaultMeta = document.querySelector('meta[name="description"]').getAttribute('content');
        this.setDefaults = function() {
        this.resource = '';
        this.token = this.token ?? null;
        this.params = {};
        this.headers = {
            'Content-Type': 'application/ld+json',
            'Accept': 'application/ld+json'
        };
        
        this.config = {
            method: 'GET',
            credentials: 'include',
            mode: 'cors',
            cache: 'no-cache',
            redirect: 'follow',
            referrerPolicy: 'no-referrer',
            headers: this.headers,
            body: null,
        };
    };        
    this.setDefaults(); 
    
    this.checkResource = function () {
            if (this.resource == '') {
            throw 'Resourece must be specified before method!';
        }   
    };
    
    this.parseParams = function() {
        let handler = '?';
        for (const [key, value] of Object.entries(this.params)) {
          handler += (`${key}=${value}&`);
        }
        
        return handler.slice(0, -1);
    };
    
    this.request = async function() {
        let response = null;
        if (this.token != null) {
            this.headers.Authorization = 'Bearer ' + this.token;
        }
        const req = await fetch(API_URL + this.resource + this.parseParams(), this.config);
        if (this.config.method === 'DELETE') {
            response =  req;
        } else {
            response =  req.json();
        }
        
        if (response.code === 401 && this.token !== null) {
            let lastReq = {
                resource: this.resource,
                params: this.params,
                config: this.config, 
            };
            
            this.refresh().then(ref => {
                if (ref.success === true) {
                    this.resource = lastReq.resource;
                    this.params = lastReq.params;
                    this.config = lastReq.config;
                    
                    return this.request();
                }                
            });
            
        }           
  
        this.setDefaults();
        return {status: req.status, response: response};
    }
    
    this.refresh = function (){
        return new Promise((resolve) => {
            this.resource = 'refresh';
            this.config.method = 'POST';  
            this.config.body = JSON.stringify({});
            this.request().then(refresh => {
                if (refresh.status == 200) {
                    refresh.response.then(data => {
                        this.token = data.token;
                        localStorage.setItem('user', 'true');
                        resolve({
                            success: true,
                            response: data,
                        });
                    });  
                }
                
                if (refresh.status != 200) { // FIXME?
                    refresh.response.then(data => { 
                        this.token = null;
                        localStorage.removeItem('user');
                        resolve({
                            success: false,
                            response: data,
                        });
                    });
                }
            });
            
        });
      }
  }
    
  get(id = null, subresource = null) {    
    this.checkResource();
    if (id != null) {
        this.resource += '/' + id;
    }

    if (subresource != null) {
        this.resource += '/' + subresource;
    }

    this.config.method = 'GET';  
    this.config.body = null;
    return this.request();
  }
  
  post(data) {
    this.checkResource();
    this.config.method = 'POST';  
    this.config.body = JSON.stringify(data);
    return this.request();
  }
  
  put(id, data) {
    this.checkResource();
    if (id != null) {
        this.resource += '/' + id;
    }
    this.config.method = 'PUT';  
    this.config.body = JSON.stringify(data);
    return this.request();
  }
  
  delete(id = null) {
      this.checkResource();
      if (id == null) {
          throw 'ID must be specified!';
      }
      
      this.resource += '/' + id;
      this.config.method = 'DELETE';  
      this.config.body = null;
      return this.request();
  }
  
  setToken(token) {
    this.token = token;
  }
  
  setLimit(limit) {
    if (limit !== null)
        this.params.limit = limit;
    
    return this;
  }
  
  setPage(page) {
    if (page !== null)
        this.params.page = page;  
    
    return this;
  }
  
  category(){
      this.resource = 'categories';     
      return this;
  }
  
  board(){
      this.resource = 'boards';     
      return this;
  }
  
  replay(){
    this.resource = 'posts';     
    return this;  
  }
  
  plot(){
      this.resource = 'plots';     
      return this;
  }
  
  posts(){
    this.resource = 'posts';     
    return this;  
  }
  
  page(){
      this.resource = 'pages';     
      return this;
  }
  
  user(){
      this.resource = 'users';     
      return this;
  }
  
  forgetPassword(){
    this.resource = 'forget-password';     
    return this;  
  }
  
  resetPassword(){
    this.resource = 'reset-password';     
    return this;  
  }
  
  skin(){
    this.resource = 'skins';     
    return this;  
  }
  
  validation(){
    this.resource = 'validation';     
    return this;    
  }
  
  config() {
    this.resource = 'configuration';     
    return this;    
  }
  
  messenger() {
    this.resource = 'one_messengers';     
    return this;   
  }
  
  message() {
    this.resource = 'messages';     
    return this;   
  }
  
  async login(data){
    return new Promise((resolve) => {
        this.resource = 'login';
        this.config.method = 'POST';  
        this.config.body = JSON.stringify(data);
        this.request().then(response => {      
            if (response.status == 200) { // FIXME?
                response.response.then(data => { 
                    this.token = data.token;
                    localStorage.setItem('user', 'true');
                    resolve({
                        success: true,
                        response: data,
                    });
                });
            }
            
            if (response.status != 200) { // FIXME?
                response.response.then(data => { 
                    this.token = null;
                    localStorage.removeItem('user');
                    resolve({
                        success: false,
                        response: data,
                    });
                });
            }
        });
    });
  }
  
  logout() {
    return new Promise((resolve) => {
        this.resource = 'logout';
        this.config.method = 'GET'; 
        this.request();
        
        this.token = null;
        localStorage.removeItem('user');
        resolve(true);
    });
  }
  
 
  
}

const obb = new OneBB();

export default obb;