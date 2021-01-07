import Vue from 'vue'
import Vuex from 'vuex'
import VuexPersist from 'vuex-persist'

Vue.use(Vuex)


const vuexLocalStorage = new VuexPersist({
    key: 'user',
    storage: window.localStorage
})

export default new Vuex.Store({
    state: {
        isLoggedIn: !!localStorage.getItem('user.token'),
        user: [],
        token: ''
    },
    mutations:{
        loginUser(state){
            state.isLoggedIn = true
        },
        logoutUser (state){
            state.isLoggedIn = false
            state.token = ''
            state.user = null
        },
        user(state,user){
            state.user = user
        },
        setToken(state,token){
            state.token = token
        }
    },
    plugins: [vuexLocalStorage.plugin],
})