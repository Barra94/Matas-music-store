<template >


    <div class="card text-white bg-dark mb-3 album_create" style="max-width: 28rem;" v-if="is_user_admin">
        <div class="card-header">Add Album</div>
        <div class="card-body">
            <form v-on:submit.prevent="onSubmit" enctype="multipart/form-data">
                <div class="form-group row">
                    <label for="colFormLabelTitle" class="col-sm-2 col-form-label col-form-label-sm">Title</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control form-control-sm" id="colFormLabelTitle" name="title" placeholder="" v-model="title">
                    </div>
                </div>


                <div class="form-group row">
                    <label for="colFormLabelGenre" class="col-sm-2 col-form-label col-form-label-sm">Genre</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control form-control-sm" id="colFormLabelGenre" name="genre" placeholder="" v-model="genre">
                    </div>
                </div>

                <div class="z-depth-1-half mb-4">
                    <img :src="image_url" class="img-fluid"
                         alt="example placeholder" style="width: 200px;height: 200px">
                </div>

                <div class="form-group ">
                    <label for="exampleFormControlFile1">Artwork</label>
                    <input type="file" class="form-control-file" id="exampleFormControlFile1" name="artwork" @change="onFileChange">
                </div>

                <div class="form-group row">
                    <label for="colFormLabelArtistID" class="col-sm-2 col-form-label col-form-label-sm">Artist</label>
                    <div class="col-sm-10">
                        <input type="text" v-model="searchInput" list="data_input" v-on:input="inputChanged" id="colFormLabelArtistID" class="form-control form-control-sm">
                        <datalist id="data_input">
                            <option v-for="item in data_input">{{item.name}}</option>
                        </datalist>
                    </div>
                </div>

                <input type="submit" class="btn btn-primary btn-sm" value="Add album">

                <div class="alert alert-success" role="alert" v-if="success_messages" style="height: auto">
                    {{success_messages}}
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
    .alert
    {
        height: auto;
    }
</style>

<script>

    import {getApiToken} from "../auth";

    export default {
        data() {
            return {
                image_url: 'https://mdbootstrap.com/img/Photos/Others/placeholder.jpg',
                data_input: [],
                searchInput:"",
                artistSelected:null,
                title: '',
                genre: '',
                image : null,

                api_token:'',
                is_user_admin:'',
                is_logged_in:'',

                error_message:'',
                success_messages:'',

            }
        },
        methods: {
            onFileChange(e)
            {
                const file = e.target.files[0];
                this.image_url = URL.createObjectURL(file);

                this.image = e.target.files[0];
            },
            inputChanged()
            {
                if(this.searchInput != "")
                {
                    //fetching the album
                    let searchLink = '../api/artist/search/' + this.searchInput;
                    window.axios.get(searchLink).then(({ data }) => {
                        this.data_input = data;
                    });
                }

            },
            onSubmit(e)
            {
                var data = new FormData();

                data.append('title', this.title);
                data.append('genre',this.genre);
                data.append('artist_name',this.searchInput);

                if(this.image != null) {data.append('artwork', this.image);}

                const config = {
                    headers: { 'content-type': 'multipart/form-data',
                               'X-Requested-With': 'XMLHttpRequest',
                                'Authorization': 'Bearer ' + this.api_token
                    },
                }

                axios.post('../api/album/create', data, config)
                    .then((response) => {
                        this.success_messages =  response.data.message;
                        this.error_message = '';
                    })
                    .catch((error) => {
                        this.error_message = error.response.data.message
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
        },

    }
</script>
