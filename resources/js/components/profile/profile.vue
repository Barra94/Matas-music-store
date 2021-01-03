<template>
    <div class="row" style="margin-top:100px;" v-if="is_logged_in">
        <div class="card col-lg-10 .col-sm-12 offset-lg-1">
            <div class="card-header">
                Profile
            </div>
            <div class="card-body">
                <div class="row">
                    <app_user_information :token="api_token"></app_user_information>
                </div>

                <!----------------Start Admin Section--------------------->
                <div class="row" v-if="is_user_admin">
                    <div class="card col-lg-8 .col-sm-12 offset-lg-2">
                        <div class=" card-body">
                            <h5 class="card-title">Admin Actions</h5>
                            <hr>
                            <div class="row">
                                <div class="col-lg-2 .col-sm-12  offset-lg-1">
                                    <router-link to="/add_album" class="btn btn-primary">Add album</router-link >
                                </div>
                                <div class="col-lg-2 .col-sm-12 offset-lg-2">
                                    <router-link to="/add_song" class="btn btn-primary">Add song</router-link >
                                </div>
                                <div class="col-lg-2 .col-sm-12 offset-lg-2">
                                    <router-link to="/add_artist" class="btn btn-primary">Add artist</router-link >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!----------------End Admin Section--------------------->

                <div class="row">
                    <app_user_albums_list :token="api_token"></app_user_albums_list>
                </div>

            </div>
        </div>
    </div>

</template>

<style>
    .profile-wrap{
        width:auto;
        max-width:1020px;
        margin: 0 auto;
        margin-top:100px;
        border:1px solid #eee;
        padding:15px;
        background:white;
        border-radius: 4px;
    }
    .card
    {
        margin-bottom:30px;
    }

    .form-group.last { margin-bottom:0px; }
</style>

<script>

    import user_information from './user_information'
    import user_albums_list from './user_album_list'
    import {getApiToken} from "../../auth";

    export default {
        data() {
            return {
                api_token:'',
                is_user_admin:'',
                is_logged_in:'',
            }
        },
        methods: {
            getTheApiToken() {
                this.api_token = getApiToken();
            },

            adminPanal()
            {
                var params = {api_token: this.api_token,};
                var headers = {'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest',};

                window.axios.get('/api/user/isAdmin',{params,headers}).then(({ data }) => {
                    this.is_logged_in = true;
                    if(data) {this.is_user_admin = true;}
                    else {this.is_user_admin = false;}
                }).catch((error) => {
                    this.is_logged_in = false;
                    this.$router.push('/login');
                });
            },

        },
        components: {
            app_user_information: user_information,
            app_user_albums_list: user_albums_list
        },
        created() {
            this.getTheApiToken();
            this.adminPanal();
        }

    }
</script>
