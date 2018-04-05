<template>
    <div  class="associated-tasks-root table-responsive">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <a class="accordion-toggle" data-toggle="collapse"  href="#associatedtasks">
                    Associated Tasks
                </a>
            </div>
            <div id="associatedtasks" class="panel-collapse collapse in table-responsive">
                <associated-tasks-list-table
                        v-if="order"
                        :showSimulator="showSimulator"
                        :activityId="activityId"
                        :selectedOrder="order">
                </associated-tasks-list-table>
            </div>
        </div>
    </div>
</template>

<script>
    //import AssociatedTasksListTable from './AssociatedTasksListTable.vue'
    //import AssociatedTasksListTable from '../../ExpediteConfirmation/OrderActivityList/OrderActivityListTable.vue'
    import AssociatedTasksListTable from './AssociatedTasksListTable.vue'

    export default {
        data () {
            return {
                order: null,
                showSimulator: false,
            }
        },
        computed: {
            location()
            {
                return this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF: '';
            }
        },
        props: {
            selectedOrder: {
                type: Object,
                default: null,
            },
            activityId: {
                type: [String, Number],
                default: '',
            },
        },
        created() {
            console.log('AssociatedTasks Component created.')
            this.getBookingInformation();
        },
        components: {
            'associated-tasks-list-table' : AssociatedTasksListTable,
        },
        mounted() {
            console.log('AssociatedTasks Component mounted.')
        },
        methods:
        {
            getBookingInformation() {
                let payload = {
                    orderId: (this.selectedOrder? this.selectedOrder.UDF1: ''),
                    quoteId: (this.selectedOrder? this.selectedOrder.quote_id?this.selectedOrder.quote_id:this.selectedOrder.QUOTE_ID : ''),
                    location: (this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF: ''),
                };
                this.$store.dispatch('getBookingInformation', payload)
                    .then((response) => {
                        console.log('getBookingInformation response=', response);
                        this.order = this.isEmpty(response.body)? null: response.body;
                    })
                    .catch((error) => {
                        console.log('getBookingInformation error=', error);
                    });
            },
        },
        watch: {
            location(newVal, oldVal) {
                // seems never triggered
                console.log('&&&&&&&&&&&&&&&&&& watch location changed');
                console.log('&&&&&&&&&&&&&&&&&& watch location changed newVal=', newVal);
                console.log('&&&&&&&&&&&&&&&&&& watch location changed oldVal=', oldVal);
                this.getBookingInformation();
            }
        }
    }
</script>

<style scoped>
    .associated-tasks-root {
        margin: 0px;
    }
    .panel > .table-responsive {
        margin: 0px;
        overflow-x: auto;
        min-height: 130px;
    }
</style>