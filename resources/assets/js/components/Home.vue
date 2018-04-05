<template>
    <div id="home">
        <app-menu></app-menu>
        <notification></notification>
        <!--<app-tabs></app-tabs>-->
        <div id="mainbody">
            <div class="mainbody-view">
                <transition :name="transition">
                    <keep-alive>
                        <router-view v-if="$route.meta.keepAlive" :key="$route.fullPath"></router-view>
                    </keep-alive>
                </transition>
                <transition :name="transition">
                    <router-view v-if="!$route.meta.keepAlive" :key="$route.fullPath"></router-view>
                </transition>
            </div>
        </div>
    </div>
</template>
<script>
import AppMenu from './AppMenu.vue'
import AppTabs from './AppTabs.vue'
import Notification from './Notification.vue'
export default {
    data() {
        return {
            transition: ''
        }
    },
    components: {
        'app-menu': AppMenu,
        'app-tabs': AppTabs,
        'notification': Notification,
    },
    beforeCreate() {
        console.log('/resources/js/Home.vue-Home beforeCreate created.')
//        this.$store.dispatch('setMenus');
//        this.$store.dispatch('setTabs');
    },
    created() {
        console.log('/resources/js/Home.vue-Home Component created.')

    },    
    mounted() {
        console.log('/resources/js/Home.vue-Home Component mounted.')
    },
    computed: {

    },
    methods: {
    }
}
</script>
<style>
.mainbody-view {
    padding-top: 10px;
	font-family: 'Open Sans', sans-serif;
	font-size: 12px;
	line-height: 1;
    background-color: #ecf0f5;
}
</style>