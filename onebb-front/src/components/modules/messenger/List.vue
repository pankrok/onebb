<template>
<div class="box-content" key="obbmsg-list">
    <div class="list">
        <Transition name="slide-fade" mode="out-in">
        <span v-if="!$store.state.messenger.cache" class="box-loader" key="msg_loader"></span>
        <span v-else key="msg_list">
        <div v-for="msg in list" :key="msg.id" class="list-item row p-2 j-c-space-between a-i-center pointer" @click="start(msg.id)">
            <div class="row w100 j-c-space-between relative">
                <div>
                    <b v-for="user in msg.users" :key="user.id">
                        <span v-html="parseUsername(user.username, user.user_group.html_code)"></span>&nbsp;
                    </b>
                </div> 
                <div>
                    {{ dateFormat(msg.updated_at) }}
                </div>
                <span v-if="isNew(msg.id, msg.updated_at)" class="badge danger">{{ $t('new') }}</span>
            </div>
        </div>
        </span>
        </Transition>
    </div>
</div>
</template>

<script>

import moment from 'moment';
export default {
  name: 'List',
  computed: {
    list() {
        return this.$store.state.messenger.cache;
    },
  },
  methods: {
    start(id) {
        this.$emit('startEvent', {event: 'chat', payload: id});
    },
    dateFormat(value) {
        return moment(String(value)).format('YYYY-MM-DD hh:mm:ss');
    },
    parseUsername(username, groupe) {
        return groupe.replace('{{username}}', username);
    },
    isNew(id, time) {
        return (this.$store.state.messenger.readMessages[id] < time);
    }
  }
}
</script>
<style scoped>
    .w100 { width:100%; }
</style>