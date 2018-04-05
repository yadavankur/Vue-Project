<template>
    <div id="dashboard">
        <div id="mainbody">
            <app-tabs></app-tabs>
            <transition name="fade" mode="out-in">
                <router-view></router-view>
            </transition>
        </div>
    </div>
</template>

<script>
import AppTabs from './AppTabs.vue'

export default {
    components: {
        'app-tabs': AppTabs,
    },
    data() {
            return {
            }
        },
        methods: {
        },
        mounted: function () {
            console.log('/resources/js/dashboard.vue-dashboard mounted router=', this.$router);
        },
        created() {
            console.log('/resources/js/dashboard.vue-dashboard Component created.')
        },         
}
</script>

