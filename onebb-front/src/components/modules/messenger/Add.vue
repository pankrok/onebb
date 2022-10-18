<template>
    <div class="box-content row" key="obbmsg-add">
    <div class="col-9 column my-2">
        <input type="text" @input="event => searchUser( event.target.value )" name="plotName" id="plotName" class="form-control" :placeholder="$t('find user')">
    </div>
    <div class="col-3 column my-2">
        <button class="btn btn-success" @click="start">{{ $t('Start chat') }}</button>
    </div>
    <span v-if="send" class="box-loader"></span>
    <Transition name="slide-fade" mode="out-in">
        <div v-if="usersList" class="col-sm-7 list" key="user-list">
            <div class="wrapper osy">
                <h5 class="my-2 text-center">{{ $t('users list') }}</h5>
                <div v-for="user in usersList" :key="user.id" class="list-item row px-2 j-c-space-between a-i-center">
                    <input type="checkbox" name="hidden" class="onoffswitch-checkbox" :id="user.id" :value="user" tabindex="0" v-model="chatUsers">
                    <label class="d-flex j-c-center a-i-center pointer" :for="user.id">
                    <img :src="$store.state.baseUrl + user.avatar" :alt="user.username" class="avatar avatar-sm">
                    <div class="col-10 col-sm-9 column">
                        <div class="small" v-html="parseUsername(user.username, user.user_group.html_code)"></div>
                    </div>
                    </label>
                </div>
            </div>
        </div>
        <div class="col-sm-7 a-i-center j-c-center d-flex" v-else key="no-users">
            <h5>{{ $t(searchmsg) }}</h5>
        </div>
     </Transition>
     <Transition name="slide-fade" mode="out-in">
        <div v-if="chatUsers.length > 0" class="col-sm-5 list">
            <div class="wrapper osy">
                 <h5 class="my-2 text-center">{{ $t('selected users') }}</h5>
                 <div v-for="user in chatUsers" :key="user.id" class="list-item row px-2 j-c-space-between a-i-center">
                    <input type="checkbox" name="hidden" class="onoffswitch-checkbox" :id="user.id" :value="user" tabindex="0" v-model="chatUsers">
                    <label class="d-flex j-c-center a-i-center pointer" :for="user.id">
                    <img :src="$store.state.baseUrl + user.avatar" :alt="user.username" class="avatar avatar-sm">
                    <div class="col-10 col-sm-9 column">
                        <div class="small" v-html="parseUsername(user.username, user.user_group.html_code)"></div>
        
                    </div>
                    </label>
                </div>
             </div>
        </div>
    </Transition>
    </div>
</template>

<script>
export default {
  name: 'Add',
  data() {
    return {
        send: false,
        name: '',
        users: [],
        chatUsers: [],
        searchmsg: '',
    }
  },
  computed: {
    usersList() {
        let u = [];
                
        this.users.forEach((e) => {
            if (e.id !== this.$store.state.onebb.status.uid) {
                u.push(e);
            }
        });
        
        if (u.length > 0) {
            return u;
        }
        
        return null;
    }
  },
  methods: {
    searchUser(name) {
        if (name.length > 2) {
            this.name = name;
            if (this.send === false) {
                this.send = true;
                this.users = [];
                setTimeout(()=>{this.request()}, 300);
            }
        }
    },
    request() {
        this.searchmsg = 'loading';
        this.$store.dispatch('onebb/get', {
            resource: 'user', 
            subresource: `?page=1&limit=20&username=${this.name}`
        }).then(response => {
            this.send = false;
            if (response['hydra:member'].length === 0) {
                this.searchmsg = 'no users found';
                return null;
            }
            
            this.users = response['hydra:member'];
        });
    },
    parseUsername(username, groupe) {
        return groupe.replace('{{username}}', username);
    },
    start() {
        this.$emit('startEvent', {event: 'add', payload: this.chatUsers});
    }
  }
}
</script>
<style scoped>

    .small {
        margin: 0;
        font-size: 12px;
    }
    
    .msg {
        margin: .5rem 0 .5rem 0 ;
        font-size: 14px;
    }
    
    
    .osy {
        overflow-y: scroll;
        max-height: 340px;
    }
    
    .wrapper {        
        margin-right: 10px;
    }
   

</style>