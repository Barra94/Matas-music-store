<template>
    <div class="">

        <app_nav></app_nav>
        <router-view></router-view>
        <app_song_player></app_song_player>

    </div>
</template>

<script>

    import Home from './components/Home.vue'
    import navbar from './components/navbar.vue'
    import album_library from './components/albums_library.vue'
    import song_player from './components/song_player.vue'

    //<album_library></album_library>
    //<home></home>

    export default {
        data() {
            return {
                albums: []
            }
        },
        methods: {
            create() {
                // To do
            },
            readAll() {
                window.axios.get('/api/albums').then(({ data }) => {
                    this.albums = data;
                    console.log(this.albums)
                });
            },
            update(id, color) {
                // To do
            },
            del(id) {
                // To do
            }
        },
        components: {
            app_nav: navbar,
            Home: Home,
            album_library:album_library,
            app_song_player: song_player
        },
        created(){
            this.readAll();
        }
    }
</script>
