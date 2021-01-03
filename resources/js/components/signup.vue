<template>
    <div class="container">
        <div class="row">
            <div class="login-wrap">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">
                            Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="inputName" placeholder="Name" required v-model="name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">
                            Email</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" id="inputUser" placeholder="email" required v-model="email">
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
                        <label class="col-sm-3 control-label">Confirm password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="inputPswConf" placeholder="password_confirmation" required v-model="password_confirmation">
                        </div>
                    </div>
                    <div class="form-group last">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit" class="btn btn-success btn-sm" @click.prevent="signup">
                                Signup</button>
                            <button type="reset" class="btn btn-default btn-sm">
                                Reset</button>
                        </div>
                    </div>
                </form>
                <div class="row" v-if="isSignUpnFailed">
                    <div class="alert alert-danger col-sm-10 offset-sm-1" role="alert">
                        <p>{{signUpFailedMessage}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<style>
    .login-wrap{
        width:420px;
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
        height:auto;
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
                password_confirmation:'',
                name:'',


                signUpFailedMessage:'Please try again.',
                isSignUpnFailed:false,
            }
        },
        methods: {
            signup()
            {
                if(this.name == ''|| this.email == '' || this.password == '' || this.password_confirmation == '')
                {
                    this.signUpFailedMessage = 'please fill in all the information.';
                    this.isSignUpnFailed = true;
                    return;
                }

                if(this.password != this.password_confirmation)
                {
                    this.signUpFailedMessage = 'Password and confirm password does not match';
                    this.isSignUpnFailed = true;
                    return;
                }
                const config = {
                    headers: {
                        'content-type': 'application/json',
                    }
                };



                var data = new FormData();

                data.append('name', this.name);
                data.append('email', this.email);
                data.append('password', this.password);
                data.append('password_confirmation', this.password_confirmation);


                window.axios.post('/api/signup',data,config).then(({ data }) => {

                    login(data.access_token);
                    this.$router.push('/');

                }).catch((error) => {
                    this.signUpFailedMessage = 'Please try again.';
                    this.isSignUpnFailed = true;
                })
            }
        },
    };
</script>
