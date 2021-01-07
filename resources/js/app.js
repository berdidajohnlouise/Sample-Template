/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.VueRouter = require('vue-router').default;
window.VueAxios = require('vue-axios').default;
window.Axios = require('axios').default;


Vue.config.devtool = false;
Vue.config.debug = false;
Vue.config.productionTip = false;
Vue.config.silent = true;

import App from './components/App.vue';
import {routes} from './routes'
import store from './store'
import api from './api/index'
import cookie from 'js-cookie';
import helper from './helper'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faHome, faClipboardList,faUserFriends,faCog,faChartLine,faBars,faAngleRight,faAngleLeft,faUserPlus,faArrowLeft} from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import Snackbar from 'vuejs-snackbar';
import Vue from 'vue';

library.add(faHome, faClipboardList,faUserFriends,faCog,faChartLine,faBars,faAngleRight,faAngleLeft,faUserPlus,faArrowLeft)
Vue.component('font-awesome-icon', FontAwesomeIcon)
Vue.component('snackbar',Snackbar)

Object.defineProperty(Vue.prototype,'$cookie',{value: cookie})
Object.defineProperty(Vue.prototype,'$api',{value: api})
Object.defineProperty(Vue.prototype,'$helper',  {value: helper})

export const router = new VueRouter({
    mode: 'history',
    history: true,
    hash: false,
    routes: routes,
    scrollBehavior (to,from,savePosition){
        return {x: 0,y:0}
    }
});


    // Guard The router if authentication is successful
    router.beforeEach((to, from, next) => {

        // check if the route requires authentication and user is not logged in
        if (to.matched.some(route => route.meta.requiresAuth) && !store.state.isLoggedIn) {

            // redirect to login page
            next({ name: 'index' })
            return
        }



        // if logged in redirect to dashboard
        if(to.path === '/' && store.state.isLoggedIn) {
            next({ name: 'dashboard' })
            return
        }

        next()
    })



new Vue(
    Vue.util.extend({
            router,
            store
        },
        App
    )
).$mount('#app');
