<template>


    <div class="card text-white bg-dark mb-3 album_create" style="max-width: 28rem;" v-if="is_user_admin">
        <div class="card-header">Add Song</div>
        <div class="card-body">
            <form v-on:submit.prevent="onSubmit" enctype="multipart/form-data">
                <div class="form-group row">
                    <label for="colFormLabelTitle" class="col-sm-2 col-form-label col-form-label-sm">Title</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control form-control-sm" id="colFormLabelTitle" name="title" placeholder="" v-model="title">
                    </div>
                </div>


                <div class="form-group ">
                    <label for="exampleFormControlFile1">Song File</label>
                    <input type="file" class="form-control-file" id="exampleFormControlFile1" name="song_file" @change="onFileChange">
                </div>

                <div class="form-group row">
                    <label for="colFormLabelArtistID" class="col-sm-2 col-form-label col-form-label-sm">Album</label>
                    <div class="col-sm-10">
                        <input type="text" v-model="searchInput" list="data_input" v-on:input="inputChanged" id="colFormLabelArtistID" class="form-control form-control-sm">
                        <datalist id="data_input">
                            <option v-for="item in data_input">{{item.title}}</option>
                        </datalist>
                    </div>
                </div>

                <input type="submit" class="btn btn-primary btn-sm" value="Add Song">

                <div class="alert alert-success" role="alert" v-if="success_messege" style="height: auto">
                    {{success_messege}}
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

    import {getApiToken} from "../auth";

    export default {
        data() {
            return {
                data_input: [],
                searchInput:"",
                artistSelected:null,
                title: '',
                song_file:'',

                api_token:'',
                is_user_admin:'',
                is_logged_in:'',

                error_message:'',
                success_messege:'',

            }
        },
        methods: {
            onFileChange(e)
            {
                this.song_file = e.target.files[0];
            },
            inputChanged()
            {
                if(this.searchInput != "")
                {
                    //fetching the album
                    let searchLink = '../api/album/search/' + this.searchInput;
                    window.axios.get(searchLink).then(({ data }) => {
                        this.data_input = data;
                    });
                }
            },
            onSubmit(e)
            {
                var data = new FormData();

                data.append('title', this.title);
                data.append('song_file', this.song_file);
                data.append('album_title',this.searchInput);

                //const config = {
                //    headers: { 'content-type': 'multipart/form-data' }
                //}

                const config = {
                    headers: { 'content-type': 'multipart/form-data',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Authorization': 'Bearer ' + this.api_token,
                    },
                }


                axios.post('../api/song/create', data, config)
                    .then((response) => {
                        //console.log('song added');
                        this.success_messege = response.data.message;
                        this.error_message = '';
                    })
                    .catch((error) =>{
                        this.error_message = error.response.data.message;
                    });

            },
            getTheApiToken() {
                this.api_token = getApiToken();
            },

            isAdmin()
            {
                var params = {api_token: this.api_token,};

                var headers = {'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest',};

                window.axios.get('/api/user/isAdmin',{params,headers}).then(({ data }) => {

                    this.is_logged_in = true;
                    if(data) {this.is_user_admin = true}
                    else
                    {this.is_user_admin = false;
                        this.$router.push("/");
                    }

                }).catch((error) => {
                    this.is_logged_in = false;
                    this.$router.push("/");
                });
            }
        },
        created(){
            this.getTheApiToken();
            this.isAdmin();
        }

    }
</script>
