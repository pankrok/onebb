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
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/CategoryView.vue')
    },
    {
      path: '/board/:slug/:id',
      name: 'Board',
      component: () => import('../views/BoardView.vue')
    },
  ]
})

export default router
