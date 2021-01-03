<template>
    <div class="library-wrap">
        <div class="form-row">
            <div class="col">
                <input type="search" class="form-control" placeholder="Search" style="width:25%" v-on:input="inputChanged" v-model="searchInput">
            </div>
        </div>

        <div class="row">
            <app_album v-for="(album, index) in albums" :album="album" @click.native="del(index)" :key="album.id"></app_album>
        </div>
    </div>

</template>

<style>
    .library-wrap{
        width:auto;
        max-width:1020px;
        margin: 0 auto;
        margin-top:100px;
        border:1px solid #eee;
        padding:15px;
        background:white;
        border-radius: 4px;
    }

    .form-group.last { margin-bottom:0px; }
</style>

<script>

    import Album from './Album.vue'

    export default {
        data() {
            return {
                albums: [],
                searchInput:'',
            }
        },
        methods: {
            readAll() {
                //Getting al the albums.
                window.axios.get('/api/albums').then(({ data }) => {
                    this.albums = data;
                });
            },
            del(){

            },
            inputChanged()
            {
                if(this.searchInput != "")
                {
                    //fetching the albums based on the search input.
                    let searchLink = '/api/album/search/' + this.searchInput;
                    window.axios.get(searchLink).then(({ data }) => {
                        this.albums = data;
                    });
                }
                else {
                    this.readAll();
                }
            },
        },
        components: {
            app_album:Album
        },
        created(){
            this.readAll();
        }
    }
</script>
