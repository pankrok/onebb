import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'


window.obbBaseUrl = document.getElementById('app').dataset.obb;

const routes = [
  {
    path: '/',
    name: 'Home', 
    component: Home,
    meta: {
      breadCrumb: [
        {
          text: 'Home'
        }
      ]
    }
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
  {
    path: '/i/:slug/:id',
    name: 'Page',
    component: () => import( '../views/Page.vue')
  },
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
    path: '/user/configuration/:id',
    name: 'UserConfig',
    component: () => import( '../views/UserConfig.vue')
  },
  {
    path: '/validation/:hash',
    name: 'Validation',
    component: () => import( '../views/EmailValidation.vue')
  },
    {
    path: '/reset-password',
    name: 'ResetPassword',
    component: () => import( '../views/ResetPassword.vue')
  }
  ,
    {
    path: '/reset-password/validation/:hash',
    name: 'ResetPasswordValidation',
    component: () => import( '../views/ResetPasswordValidation.vue')
  }
  
]

const router = createRouter({
  history: createWebHistory(window.obbBaseUrl),
  routes
})

export default router
