import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'


window.obbBaseUrl = document.getElementById('app').dataset.obb;

const routes = [
  {
    path: '/',
    name: 'Home', 
    component: Home
  },
  {
    path: '/category/:slug/:id',
    name: 'Category',
    component: () => import('../views/Category.vue')
  },
  {
    path: '/board/:slug/:id',
    name: 'Board',
    component: () => import('../views/Board.vue')
  },
  {
    path: '/plot/:slug/:id/page/:page?',
    alias: '/plot/:slug/:id/page/:page?/limit/:limit?',
    name: 'Plot',
    component: () => import('../views/Plot.vue')
  },
  // {
    // path: '/i/:slug/:id',
    // name: 'Pages',
    // component: () => import( '../views/Pages.vue')
  // },
  {
    path: '/auth/signup',
    name: 'SignUp',
    component: () => import( '../views/SignUp.vue')
  },
  {
    path: '/plot/create/:id',
    name: 'NewPlot',
    component: () => import( '../views/NewPlot.vue')
  },
  {
    path: '/user/:slug/:id',
    name: 'Profile',
    component: () => import( '../views/Profile.vue')
  },
  {
    path: '/validation/:hash',
    name: 'Validation',
    component: () => import( '../views/EmailValidation.vue')
  }
]

const router = createRouter({
  history: createWebHistory(window.obbBaseUrl),
  routes
})

export default router
