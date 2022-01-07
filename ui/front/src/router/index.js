import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '@/views/LoginView'
import RegisterView from '@/views/RegisterView'
import HomeView from '@/views/HomeView'

const routes = [
  // {
  //   path: '/about',
  //   name: 'About',
  //   // route level code-splitting
  //   // this generates a separate chunk (about.[hash].js) for this route
  //   // which is lazy-loaded when the route is visited.
  //   component: () => import(/* webpackChunkName: "about" */ '../views/About.vue')
  // }
  {
    path: '/',
    name: 'Home',
    component: HomeView,
    meta: {
      title: 'Exomoney'
    }
  },
  {
    path: '/login',
    name: 'Login',
    component: LoginView,
    meta: {
      title: 'Exomoney - Zaloguj się'
    }
  },
  {
    path: '/register',
    name: 'Register',
    component: RegisterView,
    meta: {
      title: 'Exomoney - Zarejestruj się'
    }
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

const publicPages = ['/login', '/register']

router.beforeEach((to, from, next) => {
  document.title = to.meta.title;
  const authRequired = !publicPages.includes(to.path)
  const hasToken = localStorage.getItem('token');

  if (authRequired && !hasToken) {
    // return next('/login');
  }
  next();
});

export default router
