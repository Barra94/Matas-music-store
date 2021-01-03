<template>

    <ul class="list-group">
        <li v-for="(album, index) in albums" :album="album" :key="album.id" class="list-group-item"></li>
    </ul>

</template>

<style>

</style>

<script>

    export default {
        data() {
            return {
                albums: []
            }
        },
        methods: {
        },
        created(){
        }

    }
</script>
