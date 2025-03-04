<script setup>
import {ref} from "vue"
import {useAuthStore} from "@/stores/auth.js"
import {useRouter} from "vue-router"
import {onClickOutside} from '@vueuse/core'

const authStore = useAuthStore()
const router = useRouter()

const dropdownState = ref({
    orders: false,
    shipments: false,
    user: false
})

const ordersDropdownRef = ref(null)
const shipmentsDropdownRef = ref(null)
const userDropdownRef = ref(null)

onClickOutside(ordersDropdownRef, () => dropdownState.value.orders = false)
onClickOutside(shipmentsDropdownRef, () => dropdownState.value.shipments = false)
onClickOutside(userDropdownRef, () => dropdownState.value.user = false)

const toggleDropdown = (name) => {
    Object.keys(dropdownState.value).forEach(key => {
        if (key !== name) dropdownState.value[key] = false
    });

    dropdownState.value[name] = !dropdownState.value[name];
};

const logout = () => {
    authStore.resetStore()
    router.push("/");
};
const isAdmin = authStore.isAdmin
</script>

<template>
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 text-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-6">
                    <a href="/home" class="px-4 text-gray-400">Dashboard</a>
                    <a href="/products" class="px-4 text-gray-400">Products</a>
                    <div ref="ordersDropdownRef" class="relative">
                        <button
                            @click="toggleDropdown('orders')"
                            class="flex items-center px-3 py-2 border border-transparent text-md font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition duration-150"
                            :class="{ 'bg-gray-100 dark:bg-gray-700': dropdownState.orders }"
                        >
                            <span>Orders</span>
                            <svg class="fill-current h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div v-show="dropdownState.orders"
                             class="absolute left-0 mt-2 w-40 bg-white dark:bg-gray-700 rounded-md shadow-lg border dark:border-gray-600 z-50">
                            <a href="/orders" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">All</a>
                            <a href="/orders/ordered" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">Ordered</a>
                            <a href="/orders/delivered"
                               class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">Delivered</a>
                        </div>
                    </div>
                    <div v-if="isAdmin" ref="shipmentsDropdownRef" class="relative">
                        <button
                            @click="toggleDropdown('shipments')"
                            class="flex items-center px-3 py-2 border border-transparent text-md font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition duration-150"
                            :class="{ 'bg-gray-100 dark:bg-gray-700': dropdownState.shipments }"
                        >
                            <span>Shipment</span>
                            <svg class="fill-current h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div v-show="dropdownState.shipments"
                             class="absolute left-0 mt-2 w-40 bg-white dark:bg-gray-700 rounded-md shadow-lg border dark:border-gray-600 z-50">
                            <a href="/shipments"
                               class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">All</a>
                            <a href="/shipments/delivering"
                               class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">Delivering</a>
                            <a href="/shipments/delivered"
                               class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">Delivered</a>
                            <a href="/shipments/returned"
                               class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">Returned</a>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-6">
                    <RouterLink to="/cart" v-if="!isAdmin">
                        <i class="fa-solid fa-cart-shopping" style="color: #ff2e2e;"></i>
                    </RouterLink>
                    <div ref="userDropdownRef" class="relative">
                        <button
                            @click="toggleDropdown('user')"
                            class="flex items-center px-3 py-2 border border-transparent text-md font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition duration-150"
                            :class="{ 'bg-gray-100 dark:bg-gray-700': dropdownState.user }"
                        >
                            <span>{{ authStore.user.name }}</span>
                            <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div v-show="dropdownState.user"
                             class="absolute right-0 mt-2 w-40 bg-white dark:bg-gray-700 rounded-md shadow-lg border dark:border-gray-600 z-50">
                            <a href="/profile"
                               class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">Profile</a>
                            <button @click="logout"
                                    class="block w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">
                                Log out
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>
<style scoped>
a {
    font-size: large;
    color: #acacac;
}
</style>
