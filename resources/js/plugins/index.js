import pinia from '@/stores/index.js'
import router from '@/router'

export function registerPlugins(app) {
    app.use(router).use(pinia)
}
