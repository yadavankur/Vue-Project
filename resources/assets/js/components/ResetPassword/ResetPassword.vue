<template>
    <div>
        <div class="top-menu-bg"></div>
        <div class="wrapper">
            <form name="resetPasswordForm" class="form-signin" @submit.prevent="resetPassword()" >
                <h4 class="form-signin-heading text-center">
                    <span>{{ siteName }}</span>
                </h4>
                <div class="panel panel-primary">
                    <div class="panel-heading">Reset Password</div>
                    <div class="panel-body">
                        <input type="hidden" name="token" :value="$route.params.token">
                        <div class="form-group-sm">
                            <label for="email" class="control-label">E-Mail Address</label>
                            <input id="email" type="email" class="form-control" name="email" required autofocus v-model="email">
                        </div>

                        <div class="form-group-sm">
                            <label for="password" class="control-label">Password</label>
                            <input id="password" type="password" class="form-control" name="password" required v-model="password">
                        </div>

                        <div class="form-group-sm">
                            <label for="password-confirm" class="control-label">Confirm Password</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required v-model="password_confirmation">
                        </div>
                        <notification></notification>
                        <div class="form-group-sm">
                            <button class="btn btn-sm btn-primary btn-block"  name="Submit" type="Submit">Reset Password</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import {siteName} from '../../config';
    import Notification from '../Notification.vue'
    import AppFooter from '../AppFooter.vue'

    export default {
        created() {
            console.log('ResetPassword vue created this.$route.params=', this.$route.params);
            this.token = this.$route.params.token;
        },
        components: {
            'notification': Notification,
            'app-footer': AppFooter,
        },
        computed: {
        },
        data() {
            return {
                token: '',
                email: '',
                password: '',
                password_confirmation: '',
                siteName: siteName,
            }
        },
        methods: {
            resetPassword() {
                console.log('resetPassword');
                if (this.email == '')
                {
                    this.$store.dispatch('showErrorNotification', 'Please input your email address!');
                    return;
                }
                if (this.password == '')
                {
                    this.$store.dispatch('showErrorNotification', 'Please input your password!');
                    return;
                }
                if (this.password_confirmation == '')
                {
                    this.$store.dispatch('showErrorNotification', 'Please input your Confirm Password!');
                    return;
                }
                if (this.password_confirmation !== this.password)
                {
                    this.$store.dispatch('showErrorNotification', 'Passwords are not matched, please input again!');
                    return;
                }
                // SEND EMAIL REQUEST
                let payload = {
                    token: this.token,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                };
                console.log('resetPassword payload=', payload);
                this.$store.dispatch('resetPasswordRequest', payload)
                    .then((response) => {
                        console.log('resetPasswordRequest response=', response);
                        this.$store.dispatch('showSuccessNotification', response.body.message);

                        // use the new password to login
                        const loginData = {
                            email: this.email,
                            password: this.password
                        };

                        this.$store.dispatch('loginRequest', loginData)
                            .then((response) => {
                                console.log('login vue success=', response);
                                this.$router.push({name: 'dashboard'});
                            })
                            .catch((error) => {
                                console.error('login vue error=', error);
                            });
                    })
                    .catch((error) => {
                        console.log('resetPasswordRequest error=', error);
                    });
            }
        }
    }
</script>
<style scoped>
    .wrapper{
        margin-top: 20px;
        margin-bottom: 20px;
    }
    .form-signin{
        max-width: 420px;
        /*padding: 30px 38px ;*/
        margin: 0 auto;
        /*border: 1px solid #cccccc;*/
    }
    .form-control{
        height: 35px;
        color: #0060af;
    }
    .btn-block{
        background-color: #0060af;
        height: 35px;
        margin-top: 10px;
    }
    .top-menu-bg {
        width: 100%;
        background-color: #0060af;
        min-height: 40px;
        position: relative;
    }
</style>