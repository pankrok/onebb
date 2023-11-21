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
      path: '/category/:slug/:id',
      name: 'Category',
      component: HomeView
    },
    {
      path: '/board/:slug/:id/:page?',
      name: 'Board', // @ts-ignore
      component: () => import('../views/BoardView.vue')
    },
    {
      path: '/create/plot/:id/',
      name: 'CreatePlot', // @ts-ignore
      component: () => import('../views/CreatePlotView.vue')
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
      name: 'Page',
      component: () => import( '../views/PageView.vue')
    },
    {
      path: '/user/configuration/:id',
      name: 'UserConfiguration', // @ts-ignore
      component: () => import( '../views/UserConfigView.vue')
    },
    // {
    //   path: '/validation/:hash',
    //   name: 'Validation',
    //   component: () => import( '../views/EmailValidation.vue')
    // },
    //   {
    //   path: '/reset-password',
    //   name: 'ResetPassword',
    //   component: () => import( '../views/ResetPassword.vue')
    // }
    // ,
    //   {
    //   path: '/reset-password/validation/:hash',
    //   name: 'ResetPasswordValidation',
    //   component: () => import( '../views/ResetPasswordValidation.vue')
    // }
  ]
})

export default router
