<template>


    <div class="card text-white bg-dark mb-3 album_create" style="max-width: 28rem;" v-if="is_user_admin">
        <div class="card-header">Add Song</div>
        <div class="card-body">
            <form v-on:submit.prevent="onSubmit" enctype="multipart/form-data">
                <div class="form-group row">
                    <label for="colFormLabelName" class="col-sm-2 col-form-label col-form-label-sm">Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control form-control-sm" id="colFormLabelName" name="name" placeholder="" v-model="artist_name">
                    </div>
                </div>
                <input type="submit" class="btn btn-primary btn-sm" value="Add Artist">
                <div class="alert alert-success" role="alert" v-if="success_message" style="height: auto">
                    {{success_message}}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error_message" style="height: auto">
                    {{error_message}}
                </div>
            </form>
        </div>
    </div>


</template>

<style>
    .album_image
    {
        width: 200px;
        height: 200px;
    }
    .album_create
    {
        margin: auto;
        margin-top: 20px;
    }
</style>

<script>

    import user_information from './profile/user_information'
    import user_albums_list from './profile/user_album_list'
    import {getApiToken} from "../auth";

    export default {
        data() {
            return {
                artist_name:'',

                api_token:'',
                is_logged_in:'',
                is_user_admin:'',

                success_message:'',
                error_message:'',
            }
        },
        methods: {
            onSubmit()
            {
                if(this.artist_name == '')
                {
                    this.error_message = 'please fill in all the information.';
                    this.success_messege = '';
                    return;
                }

                const config = {
                    headers: {
                        'content-type': 'application/json',
                        'Authorization': 'Bearer ' + this.api_token,
                    }
                };

                var data = new FormData();
                data.append('name', this.artist_name);

                window.axios.post('/api/artist/create',data,config).then(({ data }) => {
                    this.success_message = data.message;

                }).catch((error) => {
                    console.log(error);
                    this.error_message = 'Please try again.';
                })

            },
            isAdmin()
            {
                var params = {api_token: this.api_token,};

                var headers = {'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest',};

                window.axios.get('/api/user/isAdmin',{params,headers}).then(({ data }) => {

                    //in case the user is logged in.
                    this.is_logged_in = true;
                    if(data) {this.is_user_admin = true}
                    else
                    {
                        //The user logged in, and the user is not admin.
                        this.is_user_admin = false;
                        this.$router.push("/");
                    }

                }).catch((error) => {
                    this.is_logged_in = false;
                    this.$router.push("/");
                });
            },
            getTheApiToken() {
                this.api_token = getApiToken();
            },
        },
        created()
        {
            this.getTheApiToken();
            this.isAdmin();
        }

    }
</script>
