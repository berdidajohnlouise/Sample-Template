import Signin from '../components/pages/signin'
import Dashboard from '../components/pages/dashboard'


export const routes = [
    {
        name: 'index',
        path: '/',
        component: Signin
    },
    {
        name: 'dashboard',
        path: '/dashboard',
        component: Dashboard,
        meta: {requiresAuth: true}
    },
]
