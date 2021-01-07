<template>
    <div>Dashboard 
        <button @click="logout">Logout</button>
    </div>
</template>

<script>
export default {
    methods:{
        logout(){
          this.$store.commit('logoutUser')
          this.$router.push({name:'index'})
        },
    }
    
}
</script>


