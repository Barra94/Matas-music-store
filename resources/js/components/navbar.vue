<template>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <router-link to="/" class="navbar-brand" href="#">Matas Music Store</router-link>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <router-link to="/" class="nav-item nav-link">Home</router-link>
                    <router-link v-if="!is_logged_in" to="/login" class="nav-item nav-link">Login</router-link>
                    <router-link v-if="!is_logged_in" to="/signup" class="nav-item nav-link">Signup</router-link>
                    <router-link v-if="is_logged_in" :to="profile_url" class="nav-item nav-link">Profile</router-link>
                    <a v-if="is_logged_in"  class="nav-item nav-link" @click="c_logout">Logout</a>
                </div>
            </div>s
        </nav>
</template>

<script>

    import { logout } from '../auth.js';
    import {getApiToken} from "../auth";
    import {eventBus} from "../app";

    export default {

        data() {
            return {
                api_token:"",
                profile_url:"",
                is_logged_in:""
            }
        },
        methods: {
            //change name to refresh the variables
            isUserAdmin()
            {
                var params = {api_token: this.api_token,};
                var headers = {'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest',};

                window.axios.get('/api/user/isAdmin',{params,headers}).then(({ data }) => {
                    this.is_logged_in = true;
                    if(data) {this.profile_url = "/profile";}
                    else {this.profile_url = "/profile";}
                }).catch((error) => {
                    this.is_logged_in = false;
                    this.profile_url = "";
                });
            },
            isLogedIn()
            {
                console.log('url' + this.profile_url);
                console.log('is logged in' + this.is_logged_in);
            },
            c_logout()
            {
                logout();

                //to emit the event tokenWasChanged
                eventBus.changeApiToken(this.api_token);

                //after logout, navigate to the home page.
                if(this.$router.currentRoute.path !== '/') {this.$router.push({name:'albums_library'});}

            },
            getTheApiToken()
            {this.api_token = getApiToken();}

        },
        created() {
            eventBus.$on('TokenWasChanged' , () => {
                this.getTheApiToken();
                this.isUserAdmin();
            });

            this.getTheApiToken();
            this.isUserAdmin();
        }

    }


</script>
