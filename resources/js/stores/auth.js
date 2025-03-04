import {defineStore} from 'pinia'
import {computed, ref} from 'vue'

export const useAuthStore = defineStore('authStore', () => {
        const user = ref(null)
        const token = ref(null)

        const resetStore = () => {
            user.value = null
            token.value = null
        }
        const setUser = (newUser) => {
            user.value = newUser
        }
        const isAuthorized = computed(() => !!user.value)
        const isAdmin = computed(() => user.value.role_id === 1)

        const setToken = (newToken) => {
            token.value = newToken
        }

        return {
            user,
            token,
            setUser,
            setToken,
            resetStore,
            isAuthorized,
            isAdmin
        }
    },

    {persist: true}
)
