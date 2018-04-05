<template>
    <div id="root-dashboard-main">
        <div class="main">
            <div class="container">
                <div v-if="parseInt(user.isAdmin) == 1" class="row">
                    <admin-dashboard></admin-dashboard>
                </div>
                <div v-else-if="isAssignedResources" class="row">
                    <div class="col-lg-6">
                        <ibox title="Task Overview of Current Month">
                            <div slot="ibox-content" class="ibox-content">
                                <div class="row-content">
                                    <task-overview-doughnut-chart></task-overview-doughnut-chart>
                                </div>
                            </div>
                        </ibox>
                    </div>
                    <div class="col-lg-6">
                        <ibox title="Task Overview of Last Three Months">
                            <div slot="ibox-content" class="ibox-content">
                                <div class="row-content">
                                    <task-overview-bar-chart></task-overview-bar-chart>
                                </div>
                            </div>
                        </ibox>
                    </div>
                    <!--<div class="col-lg-4">-->
                        <!--<ibox title="Task Overview">-->
                            <!--<div slot="ibox-content" class="ibox-content">-->
                                <!--<div class="row-content">-->
                                    <!--<task-overview-line-chart></task-overview-line-chart>-->
                                <!--</div>-->
                            <!--</div>-->
                        <!--</ibox>-->
                    <!--</div>-->
                    <div class="col-lg-4">
                        <ibox title="Tasks Overdue">
                            <div slot="ibox-title">
                                <h5>Tasks</h5>
                                <span class="label label-danger">Overdue</span>
                            </div>
                            <div slot="ibox-content" class="ibox-content">
                                <div class="row-content">
                                    <task-feed dueType="OVERDUE"></task-feed>
                                </div>
                            </div>
                        </ibox>
                    </div>
                    <div class="col-lg-4">
                        <ibox title="Tasks Due Today">
                            <div slot="ibox-title">
                                <h5>Tasks</h5>
                                <span class="label label-warning">Due Today</span>
                            </div>
                            <div slot="ibox-content" class="ibox-content">
                                <div class="row-content">
                                    <task-feed dueType="DUETODAY"></task-feed>
                                </div>
                            </div>
                        </ibox>
                    </div>
                    <div class="col-lg-4">
                        <ibox title="Tasks Due Tomorrow">
                            <div slot="ibox-title">
                                <h5>Tasks</h5>
                                <span class="label label-info">Due Tomorrow</span>
                            </div>
                            <div slot="ibox-content" class="ibox-content">
                                <div class="row-content">
                                    <task-feed dueType="DUETOMORROW"></task-feed>
                                </div>
                            </div>
                        </ibox>
                    </div>
                    <div class="col-lg-12">
                        <!--<notification-list></notification-list>-->
                        <ibox title="All my notification">
                            <div slot="ibox-content" class="ibox-content">
                                <div class="row-content">
                                    <notification-list-table></notification-list-table>
                                </div>
                            </div>
                        </ibox>
                    </div>
                    <div class="col-lg-12">
                        <ibox title="All my tasks">
                            <div slot="ibox-content" class="ibox-content">
                                <div class="row-content">
                                    <task-list-table></task-list-table>
                                </div>
                            </div>
                        </ibox>
                    </div>
                    <div class="col-lg-12">
                        <ibox title="All group tasks">
                            <div slot="ibox-content" class="ibox-content">
                                <div class="row-content">
                                    <task-group-list-table></task-group-list-table>
                                </div>
                            </div>
                        </ibox>
                    </div>
                </div>
                <div v-else >
                    <div class="col-lg-12">
                        <ibox title="System Warning" :closeable="false">
                            <div slot="ibox-content" class="ibox-content">
                                <div>
                                    <h2>Sorry, you haven't been assigned any resources,<br>
                                        please contact the administrator!
                                    </h2>
                                </div>
                            </div>
                        </ibox>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
//    import NotificationList from './NotificationList.vue'
//    import TaskList from './TaskList.vue'
    import IBox from '../IBox/IBox.vue'
    import TaskFeed from './TaskList/TaskFeed.vue'

//    import TaskOverviewLineChart from '../../store/modules/TaskOverviewLineChart'
    import TaskOverviewDoughnutChart from '../../store/modules/TaskReportDoughnutChart'
    import TaskOverviewBarChart from '../../store/modules/TaskOverviewBarChart'

    import NotificationListTable from './NotificationList/NotificationListTable.vue'
    import TaskListTable from './TaskList/TaskListTable.vue'
    import TaskGroupListTable from './TaskList/TaskGroupListTable.vue'
    import AdminDashboard from './AdminDashboard.vue'

    export default {
        computed: {
            ...mapGetters({
            }),
            ...mapState({
                user: state => state.authUser,
            }),
            isAssignedResources() {
                if (!this.hasAssignedResources(this.user))
                {
                    this.title = "Warning";
                    return false;
                }
                else
                {
                    this.title = "Testing";
                    return true;
                }

            }
        },
        data () {
            return {
                title: 'test',
            }
        },
        created() {
            console.log('/resources/assets/js/components/DashboardMain/DashboardMain.vue-DashboardMain vue created!');
        },
        mounted() {
            console.log('/resources/assets/js/components/DashboardMain/DashboardMain.vue-DashboardMain vue mounted');
        },
        components: {
//            'notification-list': NotificationList,
//            'task-list': TaskList,
            'ibox': IBox,
            'task-feed': TaskFeed,

//            'task-overview-line-chart': TaskOverviewLineChart,
            'task-overview-doughnut-chart': TaskOverviewDoughnutChart,
            'task-overview-bar-chart': TaskOverviewBarChart,

            'admin-dashboard': AdminDashboard,
            'notification-list-table': NotificationListTable,
            'task-list-table': TaskListTable,
            'task-group-list-table': TaskGroupListTable,
        },
        methods: {
        }
    }
</script>

<style scoped>
</style>
