import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import store from '../store/index.js'

let acpBaseUrl = document.getElementById('app').dataset.acp;

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/manage/forum/list',
    name: 'ForumList',
    component: () => import('../views/Boards.vue')
  },
  {
    path: '/manage/forum/edit/:resource/:id',
    name: 'ForumEdit',
    component: () => import('../views/BoardsEdit.vue')
  },
  {
    path: '/manage/users/list',
    name: 'UsersList',
    component: () => import('../views/Users.vue')
  },
  {
    path: '/manage/users/edit/:resource/:id',
    name: 'UserEdit',
    component: () => import('../views/UserEdit.vue')
  },
  {
    path: '/manage/groups/list',
    name: 'GroupList',
    component: () => import('../views/GroupList.vue')
  },
  {
    path: '/manage/groups/edit/:id',
    name: 'GroupEdit',
    component: () => import('../views/GroupEdit.vue')
  },
  {
    path: '/manage/pages/list',
    name: 'PagesList',
    component: () => import('../views/Pages.vue')
  },
  {
    path: '/manage/pages/edit/:resource/:id',
    name: 'PageEdit',
    component: () => import('../views/PageEdit.vue')
  },
  {
    path: '/auth/login',
    name: 'Login',
    component: () => import('../views/Login.vue')
  },
  {
    path: '/configuration',
    name: 'Configuration',
    component: () => import('../views/Configuration.vue')
  },  
   {
    path: '/email-template/list',
    name: 'EmailList',
    component: () => import('../views/EmailList.vue')
  },
  {
    path: '/update',
    name: 'Update',
    component: () => import('../views/Update.vue')
  },
  {
    path: '/email-template/edit/:template',
    name: 'EmailEditor',
    component: () => import('../views/EmailEditor.vue')
  },
  {
    path: '/skin/list',
    name: 'SkinList',
    component: () => import('../views/SkinList.vue')
  },
  {
    path: '/skin/edit/:id',
    name: 'SkinEdit',
    component: () => import('../views/SkinEdit.vue')
  }
  ,
  {
    path: '/plugins/list',
    name: 'Plugins',
    component: () => import('../views/Plugins.vue')
  },
  {
    path: '/plugins/store',
    name: 'PluginStore',
    component: () => import('../views/PluginStore.vue')
  },
  {
    path: '/plugins/:plugin/:temp?/:script?',
    name: 'PluginControl',
    component: () => import('../views/PluginControl.vue')
  }
]

const router = createRouter({
  history: createWebHistory(acpBaseUrl),
  routes
})

router.beforeEach((to, from, next) => {  
  if (to.name !== 'Login' && store.state.onebb.status.acp === false) {
      
      if(localStorage.getItem('user') == 'true') {
        store.dispatch('onebb/refresh', {})
        .then(response => {
            if(response.success == true) {
                next();
            } else {
                next({ name: 'Login' })
            }
        });
      } else {
         next({ name: 'Login' })          
      }
  } else {
      next()
  }
})
export default router
