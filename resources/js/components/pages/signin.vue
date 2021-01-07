<template>

    <div>
        <snackbar
            ref="snackbar"
            baseSize="100px"
            :holdTime="3000"
            position="top-center"
        />
        <div class="formContent">
            <div class="custom-content">

                <div class="logo">
                        <img src="/img/veggie.png" alt="logo">
                    </div>
                <form class="form-signin" v-on:submit.prevent="submitLogin">

                    <h3>Sign In</h3>
                     <!--  <p class="note">[<i>*</i>] Indicates Required Field</p> -->
                    <div class="form-group">
                        <input type="email" class="form-control" placeholder="Email" required autofocus v-model="email">
                         <!-- <label class="textLabel">Email<span class="rq">*</span></label> -->
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Password" required v-model="password">
                         <!-- <label class="textLabel">Password<span class="rq">*</span></label> -->
                    </div>


                    <button class="btn" type="submit">Login</button>
                </form>
                <div class="line"></div>

            </div>
        </div>
    </div>

</template>

<script>
    export default {
        data(){
            return {
                email: '',
                password: '',
                loginError: false,
            }
        },
        methods:{
             submitLogin() {
                this.$api.login({email: this.email,password:this.password}).then(response=>{
                    this.$store.commit('setToken',response.data.access_token)
                    this.$api.auth.getUser().then(res=>{
                        this.$store.commit('loginUser')
                        this.$store.commit('user',{status: response.data.type, name: response.data.pic_url,email: response.data.email})
                        this.$router.push({name: 'dashboard'})
                    }).catch(error => {
                        console.log(error)
                    })

                }).catch(error => {
                    this.$refs.snackbar.error('Invalid username or password. Please Try again !')
                    this.email = '';
                    this.password = '';
                    this.loginError = true;
                })
            }
        }
    }
</script>

<style lang="scss" scoped>
    .formContent{
        background: #6FCF97;
        width: 100%;
        height: 100vh;
        -webkit-box-align: center;
        align-items: center;
        -webkit-box-pack: center;
        justify-content: center;
        position: relative;
        display: flex;
        .custom-content{
            max-width: 400px;
            width: 100%;
            padding: 20px;
            .logo{
                position: relative;
                z-index: 1;
                text-align: center;
                img{
                    width: 150px;
                }
            }
            .form-signin{
                max-width: 400px;
                width: 100%;
                background: #ffffff82;
                padding: 80px 30px 50px;
                border-radius: 20px;
                transform: translate(0, -70px);
                h3{
                    text-align: center;
                    color: #36552F;
                    font-size: 28px;
                    font-weight: 500;
                    margin: 10px 0 30px;
                }
                .form-control:focus {
                    color: #495057;
                    background-color: #fff;
                    border-color: #388430;
                    outline: 0;
                    box-shadow: none;
                }
                button{
                    background: #4EA145;
                    width: 100%;
                    color: #fff;
                    &:hover{
                        background: #388430;
                    }
                }
            }
            .line{
                width: 100%;
                height: 1.1px;
                background: #fff;
                margin-bottom: 15px;
            }
            .forgotpass{
                text-align: center;
                a{
                    color: #fff;
                    &:hover{
                        text-decoration: none;
                        color: #388430;
                    }
                }
            }
        }
    }

@media (max-width: 370px) and (orientation : portrait){
    .formContent{
        min-height: 700px;
    }
}

@media (max-width: 768px) and (orientation : landscape){
    .formContent{
        min-height: 700px;
    }
}

</style>
