<template>
    <div>
        <cpm-activity-notes-history-modal
                :isShowCPMActivityNotesHistory="isShowCPMActivityNotesHistory"
                :itemData="itemData"
                @onCloseCPMActivityNotesHistoryModal="onCloseCPMActivityNotesHistoryModal"
        >
        </cpm-activity-notes-history-modal>
        <activity-info-modal
                :isShowActivityInfo="isShowActivityInfo"
                :order="selectedOrder? selectedOrder: null"
                :actionTitle="actionTitle"
                :actionType="actionType"
                :itemData="itemData"
                @onOpenCPMActivityNotesHistoryModal="onOpenCPMActivityNotesHistoryModal"
                @onCloseActivityInfoModal="onCloseActivityInfoModal"
                @onRefreshOrderMatrixTable="onRefreshOrderMatrixTable"
        >
        </activity-info-modal>
        <div class="row-content">
            <div class="pull-left">
                <associated-tasks-filter-bar></associated-tasks-filter-bar>
            </div>
            <div class="pull-right">
                <div class="filter-bar">
                    <form class="form-inline form-group-sm">
                        <label>Per Page:</label>
                        <select class="form-control dropdown" v-model="perPage">
                            <option :value="5">5</option>
                            <option :value="10">10</option>
                            <option :value="20">20</option>
                            <option :value="50">50</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <div class="row-content">
            <associated-tasks-vue-table ref="associatedTasksVueTable"
                                         :api-url="url"
                                         :fields="fields"
                                         :per-page="perPage"
                                         :pagination-path="paginationPath"
                                         :css="css.table"
                                         :sort-order="sortOrder"
                                         trackBy="activity_id"
                                         :multi-sort="true"
                                         :append-params="moreParams"
                                         @vuetable:pagination-data="onPaginationData">
                <template slot="order-activity-slot-action" scope="props">
                    <div>
                        <button v-if="(activityId == '') || (activityId == props.rowData.activity_id)"
                                class="btn btn-primary btn-xs" @click="updateActivityDetail(props.rowData)" title="Update">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </button>
                        <button v-if="(props.rowData.status_id == 2) || (props.rowData.status_id == 3)"
                                class="btn btn-danger btn-xs" @click="showStatusDetail(props.rowData)"
                                :title="getStatusDetail(props.rowData.status_id)">
                            <span class="glyphicon glyphicon-zoom-in"></span>
                        </button>
                    </div>
                </template>
            </associated-tasks-vue-table>
            <div class="vuetable-pagination">
                <vuetable-pagination-info ref="associatedTasksPaginationInfo"
                                          info-class="pagination-info"
                ></vuetable-pagination-info>
                <vuetable-pagination ref="associatedTasksPagination"
                                     :css="css.pagination"
                                     :icons="css.icons"
                                     @vuetable-pagination:change-page="onChangePage"
                ></vuetable-pagination>
            </div>
        </div>
    </div>
</template>

<script>
    import Vuetable from 'vuetable-2/src/components/Vuetable'
    import VuetablePagination from 'vuetable-2/src/components/VuetablePagination'
    import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'
    import Vue from 'vue'
    import { mapGetters, mapState, mapActions} from 'vuex'
    import * as api from '../../../../config';
    import AssociatedTasksFilterBar from './AssociatedTasksFilterBar.vue'
    import ActivityInfoModal from '../../CustomerService/OrderActivity/ActivityInfo/ActivityInfoModal.vue'
    import CPMActivityNotesHistoryModal from '../../CPMActivityNotes/CPMActivityNotesHistoryModal.vue'

    export default {
        computed: {
            ...mapState({
                user: state => state.authUser,
            }),
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
        mounted() {
        },
        created() {
            console.log('AssociatedTasksListTable -> created');
        },
        components: {
            'associated-tasks-vue-table': Vuetable,
            VuetablePagination,
            VuetablePaginationInfo,
            'associated-tasks-filter-bar': AssociatedTasksFilterBar,
            'activity-info-modal': ActivityInfoModal,
            'cpm-activity-notes-history-modal': CPMActivityNotesHistoryModal,
        },
        data () {
            return {
                isShowCPMActivityNotesHistory: false,
                serviceId: '',
                actionType: '',
                actionTitle: '',
                isShowActivityInfo: false,
                itemData: null,
                url:'',
                paginationPath: '',
                search: '',
                perPage: 5,
                fields: [
                    {
                        title: 'Service',
                        name: 'service.name',
                        titleClass: 'text-left',
                        dataClass: 'text-left',
                    },
                    {
                        title: 'Service Group Name',
                        name: 'activity.service_group_activity.service_group.name',
                        sortField: 'cpm_service_groups.name',
                    },
                    {
                        title: 'Activity',
                        name: 'activity.name',
                        titleClass: 'text-left',
                        dataClass: 'text-left',
                    },
                    {
                        title: 'Duration (Day)',
                        name: 'duration',
                        titleClass: 'text-left',
                        dataClass: 'text-left',
                    },
                    {
                        title: 'Original Delivery Date',
                        name: 'end_date',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        callback: 'formatDate|DD/MMM/YYYY',
                    },
                    {
                        title: 'Current Delivery Date',
                        name: 'delivery_date',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        callback: 'formatDate|DD/MMM/YYYY',
                    },
                    {
                        title: 'Status',
                        name: 'status_id',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        callback: 'formatCheckBox',
                    },
                    {
                        title: 'Actions',
                        name: '__slot:order-activity-slot-action',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                    },
                ],
                css: {
                    table: {
                        tableClass: 'table table-bordered',
                        ascendingIcon: 'glyphicon glyphicon-chevron-up',
                        descendingIcon: 'glyphicon glyphicon-chevron-down'
                    },
                    pagination: {
                        wrapperClass: 'pagination',
                        activeClass: 'active',
                        disabledClass: 'disabled',
                        pageClass: 'page',
                        linkClass: 'link',
                    },
                    icons: {
                        first: 'glyphicon glyphicon-step-backward',
                        prev: 'glyphicon glyphicon-chevron-left',
                        next: 'glyphicon glyphicon-chevron-right',
                        last: 'glyphicon glyphicon-step-forward',
                    },
                },
                sortOrder: [
                    { field: 'activity_id', sortField: 'cpm_order_matrixes.start_date', direction: 'asc'}
                ],
                moreParams: {},
            }
        },
        methods: {
            onCloseCPMActivityNotesHistoryModal() {
                console.log('onCloseCPMActivityNotesHistoryModal');
                this.isShowCPMActivityNotesHistory = false;
            },
            onOpenCPMActivityNotesHistoryModal(itemData) {
                console.log('onOpenCPMActivityNotesHistoryModal');
                this.itemData = itemData;
                this.isShowCPMActivityNotesHistory = true;
            },
            updateActivityDetail(dataItem) {
                console.log('updateActivityDetail. dataItem=', dataItem);
                this.actionType = 'update';
                this.actionTitle = 'Updating Activity Information';
                this.itemData = dataItem;
                this.onOpenActivityInfoModal();
            },
            showStatusDetail(dataItem) {
                console.log('showStatusDetail. dataItem=', dataItem);
                if (dataItem.status_id == 2)
                {
                    this.actionTitle = 'Delay Detail';
                }
                else if (dataItem.status_id == 3)
                {
                    this.actionTitle = 'Deferral Detail';
                }
                this.actionType = 'view';
                this.itemData = dataItem;
                this.onOpenActivityInfoModal();
            },
            getStatusDetail(status_id) {
                console.log('getStatusDetail. status_id=', status_id);
                if (status_id == 2)
                {
                    return 'Delay';
                } else if (status_id == 3)
                {
                    return 'Deferral';
                }
                return 'Test';
            },
            formatCheckBox(value) {
                console.log('AssociatedTasksListTable -> formatCheckBox');
                //return '<div align="center"><input type="checkbox"' + (parseInt(value) == 5 ? 'checked': '')  + ' disabled></div>';
                let element = '';
                switch (parseInt(value))
                {
                    case 1:
                        element = '<span class="label label-primary">NEW</span>';
                        break;
                    case 2:
                        element = '<span class="label label-danger">DELAY</span>';
                        break;
                    case 3:
                        element = '<span class="label label-warning">DEFERRAL</span>';
                        break;
                    case 4:
                        element = '<span class="label label-info">ON TIME</span>';
                        break;
                    case 5:
                        element = '<span class="label label-success">COMPLETED</span>';
                        break;
                }
                return element;
            },
            onPaginationData (paginationData) {
                console.log('AssociatedTasksListTable -> onPaginationData');
                this.$refs.associatedTasksPagination.setPaginationData(paginationData);
                this.$refs.associatedTasksPaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                console.log('AssociatedTasksListTable -> onChangePage');
                this.$refs.associatedTasksVueTable.changePage(page)
            },
            onOpenActivityInfoModal() {
                console.log('onOpenActivityInfoModal.');
                this.isShowActivityInfo = true;
            },
            onCloseActivityInfoModal() {
                console.log('onCloseActivityInfoModal.');
                this.isShowActivityInfo = false;
            },
            onRefreshOrderMatrixTable() {
                console.log('onRefreshOrderMatrixTable.');
                if (this.$refs.associatedTasksVueTable)
                    this.$refs.associatedTasksVueTable.refresh();
            }
        },
        events: 
        {  'associated-tasks-list-filter-set' (filterData) 
            {   console.log('associated-tasks-list-filter-set Data=', filterData);
                this.url = api.currentAssociatedTasksList;
                this.moreParams = 
                {   orderId: this.selectedOrder? this.selectedOrder.order_id?this.selectedOrder.order_id: this.selectedOrder.UDF1: '',
                    quoteId: this.selectedOrder? this.selectedOrder.quote_id?this.selectedOrder.quote_id: this.selectedOrder.QUOTE_ID: '',
                    location: this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF: '',
                    tab: this.$route.path,
                    activityId: this.activityId,
                    filter: filterData,
                };
                Vue.nextTick( () => {
                    if (this.$refs.associatedTasksVueTable)
                        this.$refs.associatedTasksVueTable.refresh();
                });
            },
            'associated-tasks-list-filter-reset' () {
                console.log('associated-tasks-list-filter-reset');
                this.url = api.currentAssociatedTasksList;
                this.moreParams = {
                    orderId: this.selectedOrder? this.selectedOrder.order_id?this.selectedOrder.order_id: this.selectedOrder.UDF1: '',
                    quoteId: this.selectedOrder? this.selectedOrder.quote_id?this.selectedOrder.quote_id: this.selectedOrder.QUOTE_ID: '',
                    location: this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF: '',
                    tab: this.$route.path,
                    activityId: this.activityId,
                };
                Vue.nextTick( () => {
                    if (this.$refs.associatedTasksVueTable)
                        this.$refs.associatedTasksVueTable.refresh()
                });
            },
            'refreshAssociatedTasksListTable'() {
                console.log('events -> refreshAssociatedTasksListTable');
                Vue.nextTick( () => {
                    if (this.$refs.associatedTasksVueTable)
                        this.$refs.associatedTasksVueTable.refresh()
                });
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                console.log('AssociatedTasksListTable -> perPage');
                this.$nextTick(function() {
                    if (this.$refs.associatedTasksVueTable)
                        this.$refs.associatedTasksVueTable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                console.log('AssociatedTasksListTable -> paginationComponent');
                this.$nextTick(function() {
                    this.$refs.associatedTasksPagination.setPaginationData(this.$refs.associatedTasksVueTable.tablePagination)
                })
            },
        },
    }
</script>
<style scoped>
</style>
