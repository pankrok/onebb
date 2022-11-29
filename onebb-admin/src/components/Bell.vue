<template>
<li class="list-complete-item">
    <button id="bells" @click="bells = !bells" class="btn xs relative">
        <i class="fa-solid fa-bell"></i>
        <Transition name="fade">
            <span class="circle pulse red-background red-shadow"></span>
        </Transition>
    </button>
    <Transition name="slide-right-fade">
    <div v-if="bells" class="absolute top-3 z-1000 box top-box mr-4">           
        <ul class="list">
            <li class="list-complete-item pointer">
                <router-link :to="{ name: 'Update' }" @click="bells = false">
                    <i class="fa-solid fa-circle-up"></i> {{ $t('available updates') }}
                    <span v-if="countUpdates" class="badge-wrapper">
                        <span class="badge danger red-shadow">{{ countUpdates }}</span>
                    </span>
                </router-link>
            </li>
            <!-- FIX ME - not implemented yet -->
            <!-- <li class="list-complete-item pointer"> -->
                <!-- <i class="fa-solid fa-triangle-exclamation"></i> {{ $t('moderations requests') }} -->
                 <!-- <span v-if="moderations" class="badge-wrapper"> -->
                    <!-- <span class="badge danger">{{ moderations.length }}</span> -->
                <!-- </span> -->
            <!-- </li> -->
        </ul>
    </div>
     </Transition>
</li>  
</template>

<script>



export default {
  name: 'Bell',
  components: {
  },
  data(){
    return {
        bells: false,
    }
  },
  computed: {
    countBells() {
        let count = 0;
        count += this.$store.state.obbBell.updates.length ?? 0;
        count += this.$store.state.obbBell.updates.moderations ?? 0;
        return count;
    },
    countUpdates() {
        return this.$store.state.obbBell.updates.length ?? 0;
    }
  },
  methods: {
    loadBells() {
        this.$store.dispatch('onebb/get', { resource: 'updateCheck' }).then(response => {
            this.$store.dispatch('obbBell/setUpdates', response);
    });
    }
  },
  mounted() {
    if (!this.$store.state.onebb.status.acp) {
        setTimeout(() => {
            this.loadBells();
        }, 2000);
    } else {
        setTimeout(() => {
            this.loadBells();
        }, 500);
    } 
  }
}
</script>

