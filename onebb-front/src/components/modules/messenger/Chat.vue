<template>
<span class="chat-wrapper" key="obbmsg-chat">
<div class="box-content" >
    <div class="chat" ref="obbChat">
    <TransitionGroup name="list-complete" tag="div"  mode="out-in">
       
        <div v-for="message in messages" class="my-1 row list-complete-item"  :class="classObject(message.sender.id)" :key="message.id">
            <div class="col-sm-3 d-flex j-c-center">
                <img :src="$store.state.baseUrl + message.sender.avatar" :alt="message.sender.username" class="avatar avatar-sm">
            </div>
            <div class="msg col-sm-9 d-flex a-i-center">
                <div class="bubble">{{ message.message }}</div>
            </div>
        </div>
        
    </TransitionGroup>
    
    </div>
</div>
<div class="box-footer j-c-center mb-1">
    <input id="msg-chat-input" type="text" name="message" placeholder="Type Message ..." class="form-control col-9" @keyup.enter="submit" ref="obbbmsginput">
    <button id="msg-chat-btn col-3" type="submit" class="btn btn-secondary" @click="submit">Send <i class="fab fa-telegram-plane"></i></button>          
</div>
</span>
</template>

<script>
import moment from 'moment';

export default {
  name: 'Chat',
  data() {
    return {
        chat: [],
        text: null,
        run: true,
    }
  },
  computed: {
    messages() {
        return this.chat;
    }
  },
  
  methods: {
    scrollDown() {
        setTimeout(() => {
            this.$refs.obbChat.scrollTo({
              top: this.$refs.obbChat.scrollHeight,
              behavior: 'smooth'
            });
        }, 300);
    },
  
    classObject(id) {
        return {right: (id === this.$store.state.onebb.status.uid)};
    },
    
    submit(){
          this.$store.dispatch('onebb/post', {
            resource: 'message', 
            data: {
                om: '/api/one_messengers/' + this.$store.state.messenger.id,
                message: this.$refs.obbbmsginput.value,
                createdAt: null,
                sender: null
            }}).then(msg => {
                this.$store.dispatch('messenger/setLastMessage', msg.created_at);
                    this.$store.dispatch('messenger/setReadMessage', {
                    id: this.$store.state.messenger.id,
                    updated_at: msg.created_at
                });
                msg.sender = {
                    id: this.$store.state.onebb.status.uid,
                    avatar: this.$store.state.onebb.status.avatar,
                    username: this.$store.state.onebb.status.slug
                };
                
                this.$refs.obbbmsginput.value = '';
                if (typeof(this.chat[this.chat.length - 1]) === 'undefined') {
                    this.chat.push(msg);
                    this.scrollDown();
                }
                
                if (this.chat[this.chat.length - 1].id !== msg.id) {
                    this.chat.push(msg);
                    this.scrollDown();
                }
            });  
    },
    
    chatPooling() {
        if (this.run === false) {
            return;
        }
        let subres  = '?page1&limit50'
                    + '&om=/api/one_messengers/' +  this.$store.state.messenger.id
        if (this.$store.state.messenger.lastMessage !== null) {
            subres  += '&created_at%5Bstrictly_after%5D='
                    + moment(this.$store.state.messenger.lastMessage).format('YYYY-MM-DD HH:mm:ss')
        }
    
        this.$store.dispatch('onebb/get', {
            resource: 'message', 
            subresource: subres
        }).then(r => {
           if(typeof(r['hydra:member'][0]) !== 'undefined') {
                         
           
               
                
                this.$store.dispatch('messenger/setLastMessage', r['hydra:member'][0].created_at);
                r['hydra:member'].reverse()
                r['hydra:member'].forEach(msg => {
                    this.chat.push(msg); 
                    if (this.$store.state.messenger.readMessages[this.$store.state.messenger.id] < msg.created_at) {
                        this.$store.dispatch('messenger/decrementUnread');
                        this.$store.dispatch('messenger/setReadMessage', {
                            id: this.$store.state.messenger.id,
                            updated_at: msg.created_at,
                        });
                    }  
                });
                this.scrollDown();
            } 
           
           
        });        
    },
    

  },
  mounted() {
  this.run = true;
    this.$store.dispatch('messenger/setLastMessage', null);
    let chatPooling = () => {
        if (this.run === false) {
            return;
        }
        if (this.$store.state.onebb.status.loggedIn) {
            this.chatPooling();
        }
        setTimeout(chatPooling, 5000);
    }
    setTimeout(chatPooling, 1);
  },
  beforeUnmount() {
    this.run = false;
    this.$store.dispatch('messenger/setChatId', null); 
    this.$store.dispatch('messenger/setLastMessage', null);
  }
}
</script>
<style scoped>
    span .chat-wrapper {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .right {
        flex-direction: row-reverse;
    }
    
    .bubble {
        padding: .5rem;
        color: var(--primary);
        border: 1px solid var(--secondary);
        background-color: var(--secondary);
        border-radius: 6px;
        width:100%;
        position: relative;
    }
    
    .bubble:after,
    .bubble:before {
     position:absolute;
     right:100%;
     top:15px;
     border:solid transparent;
     border-right-color:var(--secondary);
     content:' ';
     height:0;
     width:0;
     pointer-events:none
    }
        
    .right > .msg > .bubble:after,
    .right > .msg > .bubble:before {
     left:100%;
     rotate: 180deg;
    }
    
    .chat{
        max-height: 350px;
        overflow-x: auto;
        padding-right: 0.25rem;
    }
    
    .mb-1 {
        margin-bottom: .25rem;
    }
</style>