<template>


    <div class="card song-list" >

        <div class="image_wraper ">
            <img v-if="album.artwork" :src="album.artwork" class="card-img-top album_image " alt="">
            <img v-else :src="defaultAlbumartUrl" class="card-img-top album_image " alt="">
        </div>

        <div class="card-header">
            <span class="font-weight-bold text-capitalize">{{album.title}}</span> Songs
            <button class="btn btn-success float-right" @click="buyAlbum()">buy</button>
        </div>
        <ul class="list-group list-group-flush">
            <li v-for="(song, index) in songs" :key="song.id" class="list-group-item ">
                <span class="text-capitalize">{{song.title}}</span>
                <div class="float-right">
                    <button class="btn btn-primary" v-on:click="buySong(song)">buy</button>
                    <button class="btn btn-primary" v-on:click="playSong(song)">play</button>
                </div>

            </li>
        </ul>

        <div class="alert alert-success" role="alert" v-if="success_message" style="height: auto">
            {{success_message}}
        </div>
        <div class="alert alert-danger" role="alert" v-if="error_message" style="height: auto">
            {{error_message}}
        </div>



    </div>
</template>

<style>
    .song-list
    {
        margin:auto;
        margin-top: 100px;
        max-width: 25rem;

    }
    .image_wraper
    {
        margin:auto;
        margin-top:20px;
        margin-bottom:20px;
    }
</style>

<script>
    import {getApiToken} from "../auth";
    import {eventBus} from "../app";



    export default {
        data() {
            return {
                album:"",
                songs:[],

                api_token:'',

                success_message:'',
                error_message:'',

                defaultAlbumartUrl:'/images/albums_artwork/default-album-artwork.png',

            }
        },
        methods: {
            getAlbumInfo()
            {
                //fetching the album
                let AlbumLink = '../api/album/' + this.$route.params.albumId;
                window.axios.get(AlbumLink).then(({ data }) => {
                    this.album = data;
                });

                //fetching the songs
                let SongsLink = '../api/album/songs/' + this.$route.params.albumId;
                window.axios.get(SongsLink).then(({ data }) => {
                    this.songs = data;
                });
            },
            buyAlbum()
            {
                let buyLink = '/api/user/album/buy/' + this.album.id;

                var params = {api_token: this.api_token,};

                var headers = {'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest',};

                window.axios.get(buyLink,{params,headers}).then(({ data }) => {
                    this.success_message = 'You bought the album';
                    this.error_message = '';
                }).catch((error) => {
                    this.error_message = error.response.data.message;
                    this.success_message = '';
                });
            },
            buySong(song)
            {
                let buyLink = '/api/user/song/buy/' + song.id;

                var params = {api_token: this.api_token,};

                var headers = {'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest',};

                window.axios.get(buyLink,{params,headers}).then(({ data }) => {
                    this.success_message = data.message;
                    this.error_message = '';
                }).catch((error) => {
                    this.error_message = error.response.data.message;
                    this.success_message = '';
                });
            },
            getTheApiToken() {
                this.api_token = getApiToken();
            },
            playSong(song){
                //adding the dataTime parameter is a trick to force the audio element to request the url again, without retrieving the song from the cash.
                //every time the song_url will be uniq now.

                var song_url = '/api/stream/' + song.id
                if(this.api_token){
                    song_url += '?api_token=' + this.api_token;
                    song_url += '&dateTime=' + new Date().getTime();

                    this.$store.state.song_url = song_url;
                    eventBus.changeSong();
                }
                else
                {
                    song_url += '?dateTime=' + new Date().getTime();
                    this.$store.state.song_url = song_url;
                    eventBus.changeSong();
                }

            },



        },

        created(){
            eventBus.$on('TokenWasChanged' , () => {
                this.getTheApiToken();
            });

            this.getAlbumInfo();
            this.getTheApiToken();

        }

    }
</script>
