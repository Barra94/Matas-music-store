<template>
<div class="col-lg-8 .col-sm-12 offset-lg-2">
    <div class="card row honShelAlRow">

        <div class="card-body ">
            <h5 class="card-title">My Albums</h5>
            <hr>
            <div class="row">
                <select class="form-control card col-lg-4 .col-sm-6 offset-lg-1" v-model="selectedOrder" @change="orderBy">
                    <option value="">Sort</option>
                    <option value="title/asc">Album title A-Z</option>
                    <option value="title/desc">Album title Z-A</option>
                    <option value="artist_name/asc">Artist name A-Z</option>
                    <option value="artist_name/desc">Artist name Z-A</option>
                </select>

                <select class="form-control card col-lg-4 .col-sm-6 offset-lg-2" @change="addFilter()" v-model="selectedFilter">
                    <option value="" selected >Select Genre</option>
                    <option class="text-capitalize">metal</option>
                    <option class="text-capitalize">pop</option>
                </select>
            </div>

            <hr>


            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Artist</th>
                    <th scope="col">genre</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(album, index) in albums" :key="index">
                    <th scope="row">{{album.id}}</th>
                    <td class="text-capitalize"><router-link :to="getAlbumUrl(album)">{{album.title}}</router-link></td>
                    <td class="text-capitalize">{{album.artist_name}}</td>
                    <td class="text-capitalize">{{album.genre}}</td>
                </tr>
                </tbody>
            </table>



        </div>
    </div>




</div>


</template>

<style>
    .card
    {
        margin-bottom:30px;
    }
</style>

<script>

    export default {
        props: ['token'],
                data() {
            return {
                albums: [],
                selectedFilter:"",
                selectedOrder:"",
            }
        },
        methods: {
            readAll() {

                var params = {
                    api_token: this.token,
                };

                var headers = {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                }



                window.axios.get('/api/user/albums',{params,headers}).then(({ data }) => {
                    this.albums = data;
                    //console.log(this.albums)
                }).catch((error) => {
                    console.log('not allowed');
                    return this.$router.push('/login');
                });
            },
            addFilter()
            {
                var params = {
                    api_token: this.token,
                };

                var headers = {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }

                var query = "";

                if(this.selectedFilter=="")
                {
                    query = '/api/user/albums';
                }
                else
                {
                    query = '/api/user/albums/filter/genre/' + this.selectedFilter;
                }

                window.axios.get(query,{params,headers}).then(({ data }) => {
                    this.albums = data;
                    this.selectedOrder="";
                    //console.log(this.albums)
                }).catch((error) => {
                    console.log('not allowed');
                    return this.$router.push('/login');
                });
            },
            orderBy()
            {

                var params = {api_token: this.token,};
                var headers = {'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}
                var query = "";

                if(this.selectedOrder=="")
                {
                    query = '/api/user/albums';
                }
                else
                {
                    query = '/api/user/albums/order/' + this.selectedOrder;
                }


                window.axios.get(query,{params,headers}).then(({ data }) => {
                    this.albums = data;
                    this.selectedFilter = "";
                    //console.log(this.albums)
                }).catch((error) => {
                    return this.$router.push('/login');
                });
            },
            getAlbumUrl(album)
            {
                let link = '../album/' + album.id;
                return link;
            },
        },
        created(){
            this.readAll();
        },

    }
</script>
