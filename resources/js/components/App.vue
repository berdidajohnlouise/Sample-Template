<template>
   <router-view></router-view>
</template>

<script>
    export default {
         created() {
            
            if(this.$store.state.token) {
                    this.$api.auth.getUser().then(response=>{
                        this.$store.commit('loginUser')
                        this.$store.commit('user',{status: response.data.type, name: response.data.name,email: response.data.email})
                    }).catch(error => {
                    if (error.response.status === 401 || error.response.status === 403) {
                        this.$store.commit('logoutUser')
                        this.$router.push({name: 'index'})
                    }

                });
              
            }
        }
    }
</script>
