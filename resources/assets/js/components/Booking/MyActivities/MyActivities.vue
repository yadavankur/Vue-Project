<template>
    <div id="root-my-activities-main">
        <div class="main">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <ibox title="My activity list" :closeable="false">
                            <div slot="ibox-content" class="ibox-content">
                                <div class="row-content">
                                    <task-list-table @onRowSelected="onRowSelected"></task-list-table>
                                </div>
                            </div>
                        </ibox>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12" v-if="selectedOrder" id='associated-task-list'>
                        <keep-alive>
                            <associated-tasks-list :key="selectedOrder.UDF1"
                                                   :selectedOrder="selectedOrder"
                                                   :activityId="activityId"
                            ></associated-tasks-list>
                        </keep-alive>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import IBox from '../../IBox/IBox.vue'
    import MyActivityListTable from './MyActivityListTable.vue'
    import AssociatedTasks from '../Booking/AssociatedTasks/AssociatedTasks.vue'

    export default {
        computed: {
            ...mapGetters({        }),
            ...mapState({ user: state => state.authUser, }),
        },
        data () { return { selectedOrder: null,  activityId: '', }  },
        created() {
            console.log('/resources/js/components/booking/myactivities/MyActivities.vue- MyActivities vue created!');
        },
        mounted() {
            console.log('/resources/js/components/booking/myactivities/MyActivities.vue- MyActivities vue mounted');
        },
        components: {
            'ibox': IBox,
            'task-list-table': MyActivityListTable,
            'associated-tasks-list': AssociatedTasks,
        },
        methods: {
            onRowSelected(dataItem) {
                console.log('/resources/js/components/booking/myactivities/MyActivities.vue-onRowSelected dataItem=', dataItem);
                this.selectedOrder = dataItem && dataItem.order ? dataItem.order : null;
                this.activityId = dataItem ? dataItem.activity_id : '';
            }
        }
    }
</script>

<style scoped>
</style>
