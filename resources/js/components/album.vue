<template>
    <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card" style="width: 202px; margin-top: 20px">
            <router-link :to="getAlbumUrl()">
                <img v-if="isAlbumWithArtWork()" :src="album.artwork" class="card-img-top album_image " alt="">
                <img v-else :src="defaultAlbumartUrl" class="card-img-top album_image " alt="">
            </router-link>
            <div class="card-body">
                <h6 class="card-title text-capitalize">{{album.title}}</h6>
                by <span class="card-text text-capitalize">{{album.artist_name}}</span>
            </div>
        </div>
    </div>
</template>

<style>
    .album_image
    {
        width: 200px;
        height: 200px;
    }
</style>

<script>

    export default {
        props: ['album'],
        data() {
            return {
                defaultAlbumartUrl:'/images/albums_artwork/default-album-artwork.png'
            }
        },
        methods: {
            getAlbumUrl()
            {
                //The url to navigate to the album page.
                let link = '../album/' + this.album.id;
                return link;
            },
            isAlbumWithArtWork()
            {
                if(this.album.artwork == null) {return false;}
                return true;
            },

        },
    }
</script>
