import {defineStore} from 'pinia'
import {ref} from 'vue'

export const useMainStore = defineStore('mainStore', () => {
        const loading = ref(false)

        const setLoading = () => {
            loading.value = true
        }
        const unsetLoading = () => {
            loading.value = false
        }
        return {
            loading,
            setLoading,
            unsetLoading
        }
    },

    {persist: true}
)
