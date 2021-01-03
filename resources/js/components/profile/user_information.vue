<template>

                    <div class="card col-lg-8 .col-sm-12 offset-lg-2">
                        <div class="card-body">
                             <h5 class="card-title">My Information</h5>
                             <hr>
                            <p>Name: {{user.name}}</p>
                            <p>Email: {{user.email}}</p>
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
        props:['token'],
        data() {
            return {
                user: '',
                //token:'1234',
            }
        },
        methods: {
            readAll() {

                var params = {
                    api_token: this.token,
                };

                var headers = {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }

                window.axios.get('/api/user/info',{params,headers}).then(({ data }) => {
                    this.user = data;
                });
            },
        },
        created(){
            this.readAll();
        },

    }

</script>
