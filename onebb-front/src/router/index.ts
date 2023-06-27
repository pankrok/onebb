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
    {
      path: '/user/:slug/:id',
      name: 'Profile',
      component: () => import( '../views/UserView.vue')
    },
    // {
    //   path: '/i/:slug/:id',
    //   name: 'Page',
    //   component: () => import( '../views/Page.vue')
    // },
    // {
    //   path: '/user/configuration/:id',
    //   name: 'UserConfig',
    //   component: () => import( '../views/UserConfig.vue')
    // },
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
