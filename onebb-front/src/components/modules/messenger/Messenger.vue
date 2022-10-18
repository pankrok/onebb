<template>
<span key="obbmsg">
<Transition name="slide-fade">
<div class="box messenger column" v-if="$store.state.onebb.messenger.box">
    <div class="obb-msg box-header d-flex a-i-center j-c-space-between"><h4><i class="fa-solid fa-messages"></i> OneMessage</h4>
    <div class="icos">
            <button @click="boxToggle()" type="button" class="btn btn-chat-box-tool tooltip top" data-toggle="#boards-messenger-snippet" data-tooltip="minimalise">
                <i class="fa fa-minus" data-toggle="#boards-messenger-snippet"></i>
            </button>
            <button @click="component = 'Add'" id="msg-manager-btn" type="button" class="btn btn-chat-box-tool tooltip top" data-tooltip="conversation manager">
                <i class="fa fa-plus"></i>
            </button>
            <button @click="component = 'List'" id="msg-list-btn" type="button" class="btn btn-chat-box-tool tooltip top" data-tooltip="conversations list" title="Contacts" data-widget="chat-pane-toggle">
              <i class="fa fa-comments"></i>
            </button>
            <button @click="msgToggle()" type="button" class="btn btn-chat-box-tool tooltip top" data-tooltip="close">
                <i class="fa fa-times"></i>
            </button>
          </div>
    </div>

        <Transition name="slide-fade" mode="out-in">
            <component :is="component" :key="component"  @start-event="startEvent" />
        </Transition>
</div>
</Transition>
<div class="obb-msg messenger-ico d-flex a-i-center j-c-center" @click="boxToggle()">
    <i class="fa-regular fa-envelope fa-2xl"></i>
</div>
</span>
</template>

<script>
import Add from './Add';
import List from './List';
import Chat from './Chat';

// import drag from './../../../services/drag.service.js'; FIXME - use VUEDRAG?

export default {
  name: 'Messenger',
  data(){
    return {
        component: 'List',
    }
  },
  methods: {
    msgToggle() {
        this.$store.dispatch('onebb/toggleMsg');
    },
    boxToggle() {
        this.$store.dispatch('onebb/toggleMsgBox');
    },
    startEvent(selectedUsers) {
        if (selectedUsers.event === 'add') {
            let users = [
                '/api/users/' + this.$store.state.onebb.status.uid
            ];
            selectedUsers.payload.forEach(el => {
                users.push(el['@id']);
            });
            
            this.$store.dispatch('onebb/post', {
                resource: 'messenger',
                data: {
                    users: users
                }
            }).then(r => {
                this.$store.dispatch('messenger/setChatId', r.id); 
                this.$store.dispatch('messenger/setReadMessage', {
                    id: r.id,
                    updated_at: r.updated_at,
                });
                this.component = 'Chat';
            });
        }
        
        if (selectedUsers.event === 'chat') {
           this.$store.dispatch('messenger/setChatId', selectedUsers.payload); 
           this.component = 'Chat';
        }
        
    },
  },
  components:{
    Add,
    List,
    Chat,
  },
}

</script>
<style scoped>
    .messenger {
        width: 450px;
        height: 450px;
        position: fixed;
        z-index: 999;
        bottom: 100px;
        right: 20px;
        -moz-box-shadow: 0 0 10px var(--primary);
        -webkit-box-shadow: 0 0 10px var(--primary);
        box-shadow: 0 0 10px var(--primary);
        -webkit-transition: all .3s ease-in-out;
        -moz-transition: all .3s ease-in-out;
        -ms-transition: all .3s ease-in-out;
        -o-transition: all .3s ease-in-out;
        transition: all .3s ease-in-out; 
    }
    
    .messenger-ico {  
        transform: translate3d(0px, 0px, 0px);
        background: var(--white);
        color: var(--black);
        display: flex;
        visibility: visible;
        opacity: 1;
        width: 60px;
        height: 60px;
        touch-action: none;
        user-select: none;
        position: fixed;
        z-index: 900;
        bottom: 30px;
        right: 20px;
        border-radius: 50%;
        background-size: cover;
        -moz-box-shadow: 0 0 10px var(--primary);
        -webkit-box-shadow: 0 0 10px var(--primary);
        box-shadow: 0 0 10px var(--primary);
    }
    
    .btn-chat-box-tool {
        border: 0 !important;
        color: var(--secondary);
    }
    
    .btn-chat-box-tool:hover {
        color: var(--secondary_25);
        background: transparent;
    }
    
    @media only screen and (max-width:768px) {
     .messenger {
      width:90%!important;
      left:50%;
       transform:translate(-50%,0);
     }
    }
</style >
