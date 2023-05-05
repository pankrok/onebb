import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'Home',
      component: HomeView
    },
    {
      path: '/category/:slug/:id',
      name: 'Category',
      component: HomeView
    },
    {
      path: '/board/:slug/:id/:page?',
      name: 'Board',
      component: () => import('../views/BoardView.vue')
    },
    {
      path: '/plot/:slug/:id/:page?',
      name: 'Plot',
      component: () => import('../views/PlotView.vue')
    },
  ]
})

export default router
