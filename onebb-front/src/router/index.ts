import { createRouter, createWebHistory } from 'vue-router'
// @ts-ignore
import HomeView from '../views/HomeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/category/:slug/:id/:page?',
      name: 'Category', // @ts-ignore
      component: () => import('../views/CategoryView.vue') 
    },
    {
      path: '/board/:slug/:id/:page?',
      name: 'Board', // @ts-ignore
      component: () => import('../views/BoardView.vue') 
    },
    {
      path: '/plot/:slug/:id/:page?',
      name: 'Plot', // @ts-ignore
      component: () => import('../views/PlotView.vue') 
    },
    {
      path: '/user/:slug/:id',
      name: 'Profile', // @ts-ignore
      component: () => import( '../views/UserView.vue')
    },
  ]
})

export default router
