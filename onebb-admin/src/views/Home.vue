<template>
  <div class="row" :key="$route.name">
   <div class="col-12 my-1">
        <div class="box">
            <div class="box-header">
                <h2>OneBB</h2>
            </div>
            <div v-if="stats" class="box-content">
                <div class="row">
                    <div class="col-6">
                        <div class="list">
                            <h3 class="mx-2 border-bottom">{{ $t('statistics') }}</h3>
                            <div class="list-item d-flex j-c-space-between border-0">
                                <span>{{ $t('all topics') }}:</span> <span>{{ stats.current_plots }}</span>
                            </div>
                            <div class="list-item d-flex j-c-space-between  border-0">
                                <span>{{ $t('all posts') }}:</span> <span>{{ stats.current_posts }}</span>
                            </div>
                            <div class="list-item d-flex j-c-space-between border-0">
                                <span>{{ $t('all users') }}:</span> <span>{{ stats.current_users }}</span>
                            </div>
                        </div> 
                    </div>
                    <div class="col-6">
                        <div class="list">
                            <h3 class="mx-2 border-bottom">{{ $t('system informations') }}</h3>
                            <div class="list-item d-flex j-c-space-between border-0">
                                <span>{{ $t('version') }}:</span> <span>{{ stats.version }}</span>
                            </div>
                            <div class="list-item d-flex j-c-space-between border-0">
                                <span>{{ $t('php version') }}:</span> <span>{{ stats.php_version }}</span>
                            </div>
                            <div class="list-item d-flex j-c-space-between border-0">
                                <span>{{ $t('mysql version') }}:</span> <span>{{ stats.mysql_version }}</span>
                            </div>
                        </div> 
                    </div>
            </div>
        </div>
    </div>
   <div class="col-12">
        <div class="box">
            <div class="box-header">
                <h2><i class="fa-solid fa-users"></i> {{ $t('statistics') }}</h2>
            </div>
            <div class="box-content">
                <Chart :statistics="stats" />
            </div>
        </div> 
    </div>   
  </div>
</div>
</template>
<script>
import { defineAsyncComponent } from 'vue'
export default {
  name: 'Home',
  data() {
    return {
        stats: null,
    }
  },
  mounted() {
    this.$store.dispatch('onebb/get', { resource: 'stats' }).then(response => {
        this.stats = response;
        this.$store.dispatch('loaded');
    });
  },
  beforeUnmount() {
    this.$store.dispatch('loading');
  },
  components: {
    Chart: defineAsyncComponent(() =>
        import('./../components/Chart')
    ),
  }
}
</script>
