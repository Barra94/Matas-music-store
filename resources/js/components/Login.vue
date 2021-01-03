<template>
    <div class="container">
        <div class="row">
            <div class="login-wrap">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">
                            UserName</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" id="inputUser" placeholder="UserName" required v-model="email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">
                            Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="inputPsw" placeholder="Password" required v-model="password">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" /> Remember me
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group last">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit" class="btn btn-success btn-sm" @click.prevent="login">
                                Sign in</button>
                            <button type="reset" class="btn btn-default btn-sm">
                                Reset</button>
                        </div>
                    </div>
                </form>
                <div class="row" v-if="isLoginFailed">
                    <div class="alert alert-danger col-sm-10 offset-sm-1" role="alert">
                        <p>{{loginFailedMessage}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<style>
    .login-wrap{
        width:320px;
        margin: 0 auto;
        margin-top:100px;
        border:1px solid #eee;
        padding:15px;
        background: #f8f8f8;
        border-radius: 4px;
    }
    .alert
    {
        margin-top: 20px;
        height:50px;
    }

    .form-group.last { margin-bottom:0px; }
</style>

<script>

    import {getApiToken, login, setApiToken} from "../auth";

    export default {
        data() {
            return {
                email:"",
                password:"",
                loginFailedMessage:'Wrong email or password.',
                isLoginFailed:false,
            }
        },
        methods: {
            login()
            {
                const config = {
                    headers: {
                        'content-type': 'application/json',
                    }
                };

                var data = new FormData();

                data.append('email', this.email);
                data.append('password', this.password);

                window.axios.post('/api/login',data,config).then(({ data }) => {

                    login(data.access_token);
                     this.$router.push('/');

                }).catch((error) => {
                    this.isLoginFailed = true;
                })
            }
        },
    };
</script>
