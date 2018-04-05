<template>
    <div>Not used any more</div>
    <div id="root-notification-list">
        <div class="app-row" >
            <div class="panel panel-primary">
                <!-- Default panel contents -->
                <div class="panel-heading">
                    <a class="accordion-toggle" data-toggle="collapse"  href="#notification-section">
                        All my notifications
                    </a>
                </div>
                <div id="notification-section" class="panel-collapse collapse in ">
                    <div class="row-content">
                        <notification-list-table></notification-list-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import NotificationListTable from './NotificationList/NotificationListTable.vue'

    export default {
        computed: {
            ...mapGetters({
            }),
            ...mapState({
                user: state => state.authUser,
            }),
        },
        created() {
            console.log('/resources/assets/js/components/DashboardMain/NotificationList.vue-NotificationList vue created!');
        },
        data () {
            return {}
        },
        components: {
            'notification-list-table': NotificationListTable,
        },
    }
</script>

<style scoped>

</style>