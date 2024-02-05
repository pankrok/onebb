<template>
  <div class="row" :key="$route.name">
    <Transition name="fade">
    <div v-if="count" class="col-12 my-1">
        <div class="row">
        <div class="alert warning col-12">
            <div class="alert-body text-center">
                <h2>{{ $t('aveable new update') }}</h2>
                <button class="btn btn-success col-6" >{{ $t('update') }}</button>
            </div>
        </div>
       </div>
      <div v-for="update in this.$store.state.obbBell.updates" class="box" :key="update.version">
        <div class="box-header">
            <h2>{{ update.version }}</h2>
        </div>
        <div class="box-content" v-html="update.description">      
        </div>
        <div class="box-footer">
            <small>{{ update.created_at }}</small>
        </div>
      </div>  
    </div>
    </Transition>
    <div v-if="$store.state.obbBell.updatesLoaded && count === 0" class="col-12 my-1">
        <div class="row">
        <div class="alert success col-12">
            <div class="alert-body text-center">
                <h2>{{ $t('OneBB is up to date')}}</h2>
            </div>
        </div>
        </div>
    </div>
   
  </div>
</template>
<script>

export default {
  name: 'Update',
  computed: {
    update() {
        if (this.$store.state.obbBell.updates.length < 1) {
            return this.$store.state.obbBell.updates;
        }
        return null;
    },
    count() {
      try {
        return this.$store.state.obbBell.updates.length ?? 0;
      } catch (e) {
        return 0;
      }
    }
  },
  mounted() {
    this.$store.dispatch('loaded');
  },
  beforeUnmount() {
    this.$store.dispatch('loading');
  },
}
</script>
