import {createRouter, createWebHistory} from 'vue-router'
import {useAuthStore} from "@/stores/auth.js";

const router = createRouter({
    history: createWebHistory('/'),
    routes: [
        {
            path: '/',
            name: 'welcome',
            component: () => import('@/views/Welcome.vue'),
        },
        {
            path: '/home',
            name: 'home',
            component: () => import('@/views/Home.vue'),
            meta: {requiresAuth: true}

        },
        {
            path: '/login',
            name: 'login',
            component: () => import('@/views/auth/Login.vue')
        },
        {
            path: '/register',
            name: 'register',
            component: () => import('@/views/auth/Register.vue')
        },
        {
            path: '/products',
            name: 'products',
            component: () => import('@/views/products/Index.vue'),
            meta: {requiresAuth: true}
        },
        {
            path: '/products/show/:id',
            name: 'product.show',
            component: () => import('@/views/products/Show.vue'),
            meta: {requiresAuth: true}
        },
        {
            path: '/products/create',
            name: 'product.create',
            component: () => import('@/views/products/Create.vue'),
            meta: {requiresAuth: true}
        },
        {
            path: '/orders',
            name: 'orders',
            component: () => import('@/components/order/Index.vue'),
            meta: {requiresAuth: true}
        },
        {
            path: '/orders/ordered',
            name: 'orders.ordered',
            component: () => import('@/views/orders/IndexOrdered.vue'),
            meta: {requiresAuth: true}
        },
        {
            path: '/orders/delivered',
            name: 'orders.delivered',
            component: () => import('@/views/orders/IndexDelivered.vue'),
            meta: {requiresAuth: true}
        },
        {
            path: '/orders/show/:id',
            name: 'order.show',
            component: () => import('@/views/orders/Show.vue'),
            meta: {requiresAuth: true}
        },
        {
            path: '/shipments',
            name: 'shipments',
            component: () => import('@/components/shipment/Index.vue'),
            meta: {requiresAuth: true}
        },
        {
            path: '/shipments/delivering',
            name: 'shipments.delivering',
            component: () => import('@/views/shipments/IndexBeingDelivered.vue'),
            meta: {requiresAuth: true}
        },
        {
            path: '/shipments/returned',
            name: 'shipments.returned',
            component: () => import('@/views/shipments/IndexReturned.vue'),
            meta: {requiresAuth: true}
        },
        {
            path: '/shipments/delivered',
            name: 'shipments.delivered',
            component: () => import('@/views/shipments/IndexDelivered.vue'),
            meta: {requiresAuth: true}
        },
        {
            path: '/shipments/show/:id',
            name: 'shipments.show',
            component: () => import('@/views/shipments/Show.vue'),
            meta: {requiresAuth: true}
        },
        {
            path: '/cart',
            name: 'cart',
            component: () => import('@/views/orders/Cart.vue'),
            meta: {requiresAuth: true}
        },
        {
            path: '/:pathMatch(.*)*',
            name: 'not-found',
            component: () => import('@/views/NotFound.vue'),
        },
    ]
})
router.beforeEach(async (to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!useAuthStore().isAuthorized) {
            return next({name: 'welcome'})
        }
    }
    next()
})


export default router
