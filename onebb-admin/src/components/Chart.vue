<template>
    <div height="200px">
        <canvas id="oneBbStats" ></canvas>
    </div>
</template>

<script>
import Chart from 'chart.js/auto';

export default {
  name: 'Chart',
  props: ['statistics'],
  data() {
    return {
      ctx: null,
      chart: null,
    }
  },
  watch: {
    statistics(newVal) {
          this.init(newVal);
    }
  },
  mounted() {
    if (this.statistics !== null) {
        this.init(this.statistics);
    }
  },
  methods: {
    init(s) {
        console.log(s);
        let DAYS = [];
        for(let $i = 30; $i > 0; $i--) {
            DAYS[(30-$i)] = $i;
        }
        this.ctx = document.getElementById('oneBbStats');
        this.chart = new Chart(this.ctx, {
          type: 'line',
          data: {
            labels: DAYS,
            datasets: [{
              label: this.$t('all plots'),
              backgroundColor: [
                'rgba(255, 99, 132, 0.2)'
              ],
              borderColor: [
                'rgba(255, 99, 132, 1)'
              ],
              data: JSON.parse(s.plots),
              fill: false,
            }, {
              label: this.$t('all posts'),
              backgroundColor: [
                'rgba(54, 162, 235, 0.2)'
              ],
              borderColor: [
                'rgba(54, 162, 235, 1)'
              ],
              data: JSON.parse(s.posts),
              fill: false,
            }, {
              label: this.$t('all users'),
              fill: false,
              backgroundColor: [
                'rgba(75, 192, 192, 0.2)'
              ],
              borderColor: [
                'rgba(75, 192, 192, 1)'
              ],
              data: JSON.parse(s.users),
            }, {
              label: this.$t(' plots per day '),
              backgroundColor: [
                'rgba(255,140,0, 0.2)'
              ],
              borderColor: [
                'rgba(255,140,0, 1)'
              ],
              data: JSON.parse(s.plots_per_day),
              fill: false,
            }, {
              label: this.$t('posts per day '),
              backgroundColor: [
                'rgba(255,20,147, 0.2)'
              ],
              borderColor: [
                'rgba(255,20,147, 1)'
              ],
              data: JSON.parse(s.posts_per_day),
              fill: false,
            }, {
              label: this.$t('users per day'),
              backgroundColor: [
                'rgba(255,255,0, 0.2)'
              ],
              borderColor: [
                'rgba(255,255,0, 1)'
              ],
              data: JSON.parse(s.users_per_day),
              fill: false,
            }]
          },
          options: {
            responsive: true,
            title: {
              display: false
            },
            tooltips: {
              mode: 'index', 
              intersect: false,
            },
            hover: {
              mode: 'nearest',
              intersect: true
            },
            scales: {
              xAxes:{},
              yAxes:{},
            }
          }
        });
      }
  }
}


</script>