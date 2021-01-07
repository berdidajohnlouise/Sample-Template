import axios from 'axios'
import store from '../store'

const webClient = axios.create();

export default {
    auth: {
        setBearerToken(){
            return {
                headers: {
                    Authorization: `Bearer ${store.state.token}`
                }
            }
        },
        getUser(){
            if(store.state.token){
                return webClient.get('/api/user',this.setBearerToken())
            }
        },



    },
    login(payload){
        return webClient.post('/api/auth/login',payload)
    },

}
