<template>
  <div id="appmain" >
    <div id="mainbody" >
        <app-loading></app-loading>
        <transition name="fade" mode="out-in">
            <router-view></router-view>
        </transition>
    </div>
    <div id="mainfooter">
        <app-footer></app-footer>
    </div>
  </div>
</template>

<script>
    import Vue from 'vue';
    import {mapGetters,mapState} from 'vuex'
    import AppFooter from './AppFooter.vue'
    import AppLoading from './AppLoading.vue'
    import jwtToken from './../helpers/jwt-token';
    import * as api from './../config'
    import * as types from './../mutation-types';
    export default {
        created() {
            console.log('/resources/js/components/AppMain.vue--AppMain vue created store=', this.$store);
            if(jwtToken.getToken()) {
                this.$store.dispatch('setAuthUser');
            }
        },
        components: {
            'app-footer': AppFooter,
            'app-loading': AppLoading,
        },
        computed: {
            ...mapState({
                isLoading : state => state.loading.isLoading,
            }),
        },
        data () {
            return {
            }
        },
        beforeDestroy () {
            console.log('/resources/js/components/AppMain.vue-$$$$$$$$$$$$$ AppMain vue beforeDestroy ');
        },
    }
</script>
<style lang="">
    .ivu-select-dropdown {
        position: absolute !important;
    }
    .swal2-modal .swal2-styled+.swal2-styled {
        margin-top: 0px !important;
    }
</style>