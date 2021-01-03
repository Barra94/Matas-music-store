<template>

    <div class="audo_player">
        <audio ref="myPlayer" controls class="player">
            <source :src="song_url" type="audio/mpeg" ref="source">
            Your browser does not support HTML5 video.
        </audio>
    </div>

</template>

<style>
    .audo_player {
        height: 55px;
        position: fixed;
        bottom: 0%;
        width: 100%;
        background-color: #393838;
        opacity: 1;
    }
    .player
    {
        display:block;
        margin: auto;
    }

</style>

<script>
    import {eventBus} from "../app";

    export default {
        computed:{
            song_url()
            {
                return this.$store.state.song_url;
            }
        },
        data() {
            return {

            }
        },
        methods: {
            reloadTheSongsPlayer(){
                this.$refs.source.src = this.$store.state.song_url;
                this.$refs.myPlayer.load();
                this.$refs.myPlayer.play();
            }
        },
        created(){
            eventBus.$on('SongWasChanged' , () => {
                this.reloadTheSongsPlayer();
            });
        }

    }
</script>
