import { createRouter, createWebHistory } from "vue-router";
import authService from "../services/authService";
import LoginView from "../views/LoginView.vue";
import PurchasesView from "../views/PurchasesView.vue";

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: "/",
            name: "home",
            redirect: "/purchases",
            meta: {
                requiresAuth: true,
            },
        },
        {
            path: "/purchases",
            name: "purchases",
            component: PurchasesView,
            meta: {
                requiresAuth: true,
            },
        },
        {
            path: "/purchases/:store_id",
            name: "purchasesWithStoreId",
            component: PurchasesView,
            meta: {
                requiresAuth: true,
            },
        },
        {
            path: "/login",
            name: "login",
            component: LoginView,
            meta: {
                onlyGuests: true,
            },
        },
    ],
});

router.beforeEach((to, from, next) => {
    if (to.matched.some((record) => record.meta.requiresAuth)) {
        if (!authService.getToken()) {
            next("/login");
        } else {
            next();
        }
    } else if (to.matched.some((record) => record.meta.onlyGuests)) {
        if (authService.getToken()) {
            next("/");
        } else {
            next();
        }
    } else {
        next();
    }
});

export default router;
