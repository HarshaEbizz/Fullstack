import { createRouter, createWebHistory } from 'vue-router'
import Home from "../js/components/pages/Home.vue";
import Tags from '../js/components/pages/Tags.vue';
import Login from "../js/components/pages/Login.vue";
import Register from "../js/components/pages/Register.vue";
import Category from "../js/components/pages/Category.vue";
import { useUserStore } from "./store/user";

const history = createWebHistory();
const routes = [
    {
        path: '/register',
        component: Register,
        meta: {
            guest: true
        }
    },
    {
        path: '/login',
        component: Login,
        meta: {
            guest: true
        }
    },
    {
        path: '/',
        component: Home,
        meta: {
            requiresAuth: true,
        }
    },
    {
        path: '/tags',
        component: Tags,
        meta: {
            requiresAuth: true,
        }
    },
    {
        path: '/categories',
        component: Category,
        meta: {
            requiresAuth: true,
        }
    },
]

const router = createRouter({
    linkExactActiveClass: "active_class",
    history,
    routes
});
router.beforeEach((to, from, next) => {
    if (to.matched.some((record) => record.meta.requiresAuth)) {
        if (localStorage.getItem("token") == null) {
            next({
                path: "/login",
                component: Login,
            });
        } else {
            const userStore = useUserStore();
            if (userStore.user) {
                next();
            } else {
                next({
                    path: "/login",
                    component: Login,
                });
            }
            //  userStore.fetch_user()
            //     .then(() => {
            //         if (userStore.user) {
            //             next();
            //         } else {
            //             next('/login');
            //         }
            //     });
        }
    } else if (to.matched.some(record => record.meta.guest)){
        if (localStorage.getItem("token") == null) { 
            next();
        } else {
            next({
                path: "/",
                component: Home,
            });
        }
    }
    else {
        next();
    }
});
export default router;
