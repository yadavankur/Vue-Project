<template>
    <div>
        <task-mapping-modal
                :isShowModal="isShowModal"
                :title="title"
                :actionType="actionType"
                :itemData="itemData"
                @onCloseTaskMappingModal="onCloseTaskMappingModal"
        >
        </task-mapping-modal>
        <div class="row-content">
            <div class="pull-left">
                <task-mapping-filter-bar
                        ref="taskMappingFilterBar"
                        @onNewTaskMapping="onNewTaskMapping"
                >
                </task-mapping-filter-bar>
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
            <task-mapping-vue-table ref="taskMappingVueTable"
                                 :api-url="url"
                                 :fields="fields"
                                 :per-page="perPage"
                                 :pagination-path="paginationPath"
                                 :css="css.table"
                                 :sort-order="sortOrder"
                                 trackBy="id"
                                 :multi-sort="true"
                                 :append-params="moreParams"
                                 @vuetable:pagination-data="onPaginationData">
                <template slot="task-mapping-slot-action" scope="props">
                    <div>
                        <button class="btn btn-primary btn-sm" @click="updateTaskMapping(props.rowData)" title="Update">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </button>
                        <button class="btn btn-danger btn-sm" @click="deleteTaskMapping(props.rowData)" title="Delete">
                            <span class="glyphicon glyphicon-remove"></span>
                        </button>
                    </div>
                </template>
            </task-mapping-vue-table>
            <div class="vuetable-pagination">
                <vuetable-pagination-info ref="taskMappingPaginationInfo"
                                          info-class="pagination-info"
                ></vuetable-pagination-info>
                <vuetable-pagination ref="taskMappingPagination"
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
    import * as api from '../../../config';
    import TaskMappingFilterBar from './TaskMappingFilterBar.vue'
    import TaskMappingModal from './TaskMappingCrudModal.vue'

    export default {
        computed: {
            ...mapState({
                user: state => state.authUser,
            }),
        },
        props: {
        },
        mounted() {
        },
        created() {
            console.log('TaskMappingListTable -> created');
        },
        components: {
            'task-mapping-vue-table': Vuetable,
            VuetablePagination,
            VuetablePaginationInfo,
            'task-mapping-filter-bar': TaskMappingFilterBar,
            'task-mapping-modal': TaskMappingModal,
        },
        data () {
            return {
                title: '',
                isShowModal: false,
                itemData: null,
                actionType: '',
                url: api.currentTaskMappingList,
                paginationPath: '',
                search: '',
                perPage: 5,
                fields: [
                    {
                        title: 'Location',
                        name: 'location.name',
                        titleClass: 'text-left',
                        dataClass: 'text-left',
                    },
                    {
                        title: 'Task Name',
                        name: 'task_name',
                        titleClass: 'text-left',
                        dataClass: 'text-left',
                    },
                    {
                        title: 'Activity Name',
                        name: 'activity.name',
                        titleClass: 'text-left',
                        dataClass: 'text-left',
                    },
                    {
                        title: 'Comment',
                        name: 'comment',
                        titleClass: 'text-left',
                        dataClass: 'text-left',
                    },
                    {
                        title: 'Actions',
                        name: '__slot:task-mapping-slot-action',
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
                    { field: 'id', sortField: 'id', direction: 'asc'}
                ],
                moreParams: {},
            }
        },
        methods: {
            onNewTaskMapping() {
                console.log('onNewTaskMapping.');
                this.title = 'Adding New Task Mapping';
                this.itemData = null;
                this.actionType = 'added';
                this.onOpenTaskMappingModal();
            },
            updateTaskMapping(dataItem) {
                console.log('updateTaskMapping. dataItem=', dataItem);
                this.title = 'Updating Task Mapping';
                this.itemData = dataItem;
                this.actionType = 'updated';
                this.onOpenTaskMappingModal();
            },
            deleteTaskMapping(dataItem) {
                console.log('deleteTaskMapping. dataItem=', dataItem);
                // delete
                this.$swal({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this mapping!',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'cancel',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    allowOutsideClick: false
                })
                    .then(() => {
//                        let formData = {
//                            id: dataItem.id,
//                            task_name: dataItem.task_name,
//                            comment: dataItem.comment,
//                            type: 'deleted',
//                        };
                        //let formData = dataItem;
                        let formData = JSON.parse(JSON.stringify(dataItem));
                        formData.type = 'deleted';
                        this.$store.dispatch('updateTaskMappingRequest', formData)
                            .then((response) => {
                                console.log('updateTaskMappingRequest fire events -> refreshTaskMappingListTable');
                                this.$events.fire('refreshTaskMappingListTable');
                            })
                            .catch((error) => {});
                    }, (dismiss) => {});
            },
            onPaginationData (paginationData) {
                console.log('TaskMappingListTable -> onPaginationData');
                this.$refs.taskMappingPagination.setPaginationData(paginationData);
                this.$refs.taskMappingPaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                console.log('TaskMappingListTable -> onChangePage');
                this.$refs.taskMappingVueTable.changePage(page)
            },
            onOpenTaskMappingModal() {
                console.log('onOpenTaskMappingModal.');
                this.isShowModal = true;
            },
            onCloseTaskMappingModal() {
                console.log('onCloseTaskMappingModal.');
                this.isShowModal = false;
            },
        },
        events: {
            'task-mapping-list-filter-set' (filterText) {
                console.log('task-mapping-list-filter-set Data=', filterText);
                this.moreParams = {
                    filter: filterText,
                };
                Vue.nextTick( () => {
                    if (this.$refs.taskMappingVueTable)
                        this.$refs.taskMappingVueTable.refresh();
                });
            },
            'task-mapping-list-filter-reset' () {
                console.log('task-mapping-list-filter-reset');
                this.moreParams = {
                    filter: '',
                };
                Vue.nextTick( () => {
                    if (this.$refs.taskMappingVueTable)
                        this.$refs.taskMappingVueTable.refresh()
                });
            },
            'refreshTaskMappingListTable'() {
                console.log('events -> refreshTaskMappingListTable');
                Vue.nextTick( () => {
                    if (this.$refs.taskMappingVueTable)
                        this.$refs.taskMappingVueTable.refresh()
                });
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                console.log('TaskMappingListTable -> perPage');
                this.$nextTick(function() {
                    if (this.$refs.taskMappingVueTable)
                        this.$refs.taskMappingVueTable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                console.log('TaskMappingListTable -> paginationComponent');
                this.$nextTick(function() {
                    this.$refs.taskMappingPagination.setPaginationData(this.$refs.taskMappingVueTable.tablePagination)
                })
            },
        },
    }
</script>
<style scoped>
</style>
