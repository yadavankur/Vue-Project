<template>
    <div class="login">
        <retrieve-password
                :isShowModal="isShowRetrievePasswordModal"
                @onCloseRetrievePasswordModal="onCloseRetrievePasswordModal">
        </retrieve-password>
        <div class="top-menu-bg">
        </div>
        <div class="wrapper">
            <form name="Login_Form" class="form-signin" @submit.prevent="login()" >
                <!--<div class="row text-center bol"><i class="fa fa-circle"></i></div>-->
                <h3 class="form-signin-heading text-center">
                    <img src="../../img/logo.png" alt="Dowell"/>
                </h3>
                <hr class="spartan">
                <h4 class="form-signin-heading text-center">
                    <span>{{ siteName }}</span>
                </h4>
                <div class="input-group form-group" :class="{ 'has-error' : errors.email}">
                    <span class="input-group-addon" id="sizing-addon1">
                        <i class="fa fa-user"></i>
                    </span>
                    <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required="" autofocus="" v-model="email">
                    <span class="help-block" v-if="errors.email">{{ errors.email }}</span>
                </div>
                <div class="input-group form-group" :class="{ 'has-error' : errors.password}">
                    <span class="input-group-addon" id="sizing-addon1">
                        <i class="fa fa-lock"></i>
                    </span>
                    <input type="password" id="inputPassword" class="form-control" placeholder="Password" required="" v-model="password">
                    <span class="help-block" v-if="errors.password">{{ errors.password }}</span>
                </div>
                <notification></notification>
                <div class="form-group">
                    <button class="btn btn-lg btn-primary btn-block"  name="Submit" type="Submit">Login</button>
                </div>
                <div class="form-group">
                    <a @click="resetPassword()">Forgot Password?</a>
                </div>
            </form>
        </div>
    </div>
</template>
<script>

import {siteName} from './../config';
import {mapState} from 'vuex';
import Notification from './Notification.vue'
import * as types from './../mutation-types'
import RetrievePassword from './ResetPassword/RetrievePassword.vue'

export default {
    components: {
        'notification': Notification,
        'retrieve-password' : RetrievePassword,
    },
    created() {
        // close all modal if this comes from the error redirect
        const body = document.body;
        body.style.paddingRight = null;
        body.classList.remove('modal-open');
        // close all error notification
        // this.$store.dispatch('hideAllNotifications');

        let browser=this.getBrowser();
        console.log('/resources/js/Login.vue-browser=', browser);
        if (!this.checkBrowserVersion(browser))
        {
            this.$router.push({name: 'browserwarning'});
        }
    },
    data() {
        return {
            siteName: siteName,
            email: null,
            password: null,
            isShowRetrievePasswordModal: false,
        }
    },
    computed: {
        ...mapState({
            errors: state => state.login.errors
        })
    },
    methods: {
        checkBrowserVersion(browser) {
            switch(browser.name){
                case 'Firefox':
                    if (browser.version < 45)
                        return false;
                    break;
                case 'IE':
                    if (browser.version < 11)
                        return false;
                    break;
                case 'Chrome':
                    if (browser.version < 56)
                        return false;
                    break;
                case 'Safari':
                    if (browser.version < 9)
                        return false;
                    break;
                case 'Opera':
                    if (browser.version < 14)
                        return false;
                    break;
            }
            return true;
        },
        getBrowser() {
            var ua=navigator.userAgent,tem,M=ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
            if(/trident/i.test(M[1])){
                tem=/\brv[ :]+(\d+)/g.exec(ua) || [];
                return {name:'IE',version:(tem[1]||'')};
            }
            if(M[1]==='Chrome'){
                tem=ua.match(/\bOPR|Edge\/(\d+)/)
                if(tem!=null)   {return {name:'Opera', version:tem[1]};}
            }
            M=M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
            if((tem=ua.match(/version\/(\d+)/i))!=null) {M.splice(1,1,tem[1]);}
            return {
                name: M[0],
                version: M[1]
            };
        },
        clearErrors(event) {
            console.log('/resources/js/components/Login.vue-clearErrors=',event);
            //this.$store.dispatch('hideErrorNotification');
        }, 
        login() {
            console.log('/resources/js/components/Login.vue-login--->');
            const loginData = {
                email: this.email,
                password: this.password
            };

            this.$store.dispatch('loginRequest', loginData)
                .then((response) => {
                    console.log('/resources/js/components/Login.vue-login vue success=', response);
                    this.$router.push({name: 'dashboard'});
                })
                .catch((error) => {
                    console.error('/resources/js/components/Login.vue-login vue error=', error);
                });
        },
        resetPassword() {
            console.log('/resources/js/components/Login.vue-resetPassword');
            this.isShowRetrievePasswordModal = true;
        },
        onCloseRetrievePasswordModal() {
            console.log('/resources/js/components/Login.vue-onCloseRetrievePasswordModal');
            this.isShowRetrievePasswordModal = false;
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
  padding: 30px 38px ;
  margin: 0 auto;
  border: 1px solid #cccccc;
}
.input-group{
    height: 45px;
    margin-bottom: 15px;
    border-radius: 0px;
    color: #0060af;
}
.form-control{
    height: 45px;
    color: #0060af;
}
.input-group:hover span i{
    color: #0060af;
}
.btn-block{
    background-color: #0060af;

}
.top-menu-bg {
    width: 100%;
    background-color: #0060af;
    min-height: 40px;
    position: relative;
}
</style>