import { createRouter, createWebHistory } from 'vue-router'
// @ts-ignore
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
    {
      path: '/i/:slug/:id',
      name: 'Page', // @ts-ignore
      component: () => import( '../views/PageView.vue')
    },
    {
      path: '/create/plot/:id/',
      name: 'CreatePlot', // @ts-ignore
      component: () => import('../views/NewPlotView.vue')
    },
    {
      path: '/forget-password',
      name: 'ForgetPassword', // @ts-ignore
      component: () => import('../views/ForgetPassView.vue')
    },
    {
      path: '/reset-password/validation/:hash',
      name: 'ResetPasswordValidation', // @ts-ignore
      component: () => import( '../views/ResetPasswordValidationView.vue')
    }
  ]
})

export default router
