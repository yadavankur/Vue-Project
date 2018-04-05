<template>
    <div>
        <div>
            <cpmactivity-crud-modal></cpmactivity-crud-modal>
            <associated-user-modal
                :activityId="activityId"
                :serviceId="serviceId"
                :isShowAssociatedUser="isShowAssociatedUser"
                @onCloseAssociatedUserModal="onCloseAssociatedUserModal"
            >
            </associated-user-modal>
            <div class="pull-left">
                <cpm-activity-custom-filter-bar
                        @onClickNew="onClickNew">
                </cpm-activity-custom-filter-bar>
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

        <vuetable ref="cpmActivityvuetable"
                  :api-url="url"
                  :fields="fields"
                  :per-page="perPage"
                  :pagination-path="paginationPath"
                  :css="css.table"
                  :sort-order="sortOrder"
                  :multi-sort="true"
                  @CustomAction:action-item="onActions"
                  :append-params="moreParams"
                  @vuetable:pagination-data="onPaginationData"
        ></vuetable>
<div class="checkbox" v-for="field in fields">
    <label>
        <input type="checkbox" v-model="field.visible">
        @{{ field.title == '' ? field.name.replace('__', '') : field.title }}
    </label>
</div>
        <div class="vuetable-pagination">
            <vuetable-pagination-info ref="cpmActivitypaginationInfo"
                                      info-class="pagination-info"
            ></vuetable-pagination-info>
            <vuetable-pagination ref="cpmActivitypagination"
                                 :css="css.pagination"
                                 :icons="css.icons"
                                 @vuetable-pagination:change-page="onChangePage"
            ></vuetable-pagination>
        </div>
    </div>
</template>

<script>
    import Vuetable from 'vuetable-2/src/components/Vuetable'
    import VuetablePagination from 'vuetable-2/src/components/VuetablePagination'
    import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'
    import Vue from 'vue'
    import * as api from './../../../../config';
    import CpmActivityCustomActions from './CpmActivityCustomActions.vue'
    import CpmActivityCustomFilterBar from './CpmActivityCustomFilterBar.vue'
    import AssociatedUserModal from './AssociatedUser/AssociatedUserModal.vue'
    import CpmActivityCrudModal from './CpmActivityCrudModal.vue'
    //import VueEvents from 'vue-events'
    //Vue.use(VueEvents)
    //import columnVisibility from 'vuetable2-column-visibility'

    Vue.component('cpm-activity-custom-actions', CpmActivityCustomActions);

    export default 
    {
        computed: {     url(){   return api.currentCpmActivityNodes;    }    },
        props: {      },
        components: 
        {     Vuetable,    VuetablePagination,   VuetablePaginationInfo,
            'cpm-activity-custom-filter-bar': CpmActivityCustomFilterBar,
            'associated-user-modal': AssociatedUserModal,
            'cpmactivity-crud-modal': CpmActivityCrudModal,
        },
        data () 
        { return {       paginationPath: '',    search: '',       perPage: 5,
                fields: 
                [
                    {  title: 'Location',       name: 'service.location.name',    sortField: 'locations.name',  },
                    {  title: 'Service Name',  name: 'service.name',  sortField: 'cpm_services.name',    },
                    {   title: 'Service Group Name',
                        name: 'service_group_activity.service_group.name',
                        sortField: 'cpm_service_groups.name',
                    },
                    {   title: 'Activity Name',   name: 'name',  sortField: 'cpm_activities.name',   },
                    {  title: 'SQL Statement1',  name: 'sql_statement',   dataClass: 'sql-statement', visible: false,    },
                    {   title: 'Duration',  name: 'duration',    },
                    {   title: 'Action SQL',  name: 'action_sql',   dataClass: 'sql-statement', visible: false,    },
                    {   title: 'Actions',    name: '__component:cpm-activity-custom-actions',
                        titleClass: 'text-center',   dataClass: 'text-center'
                    }
                ],
                css: {
                    table: {
                        tableClass: 'table table-bordered table-striped table-hover',
                        ascendingIcon: 'glyphicon glyphicon-chevron-up',
                        descendingIcon: 'glyphicon glyphicon-chevron-down'
                        },
                    pagination: 
                    {
                        wrapperClass: 'pagination',  activeClass: 'active',    disabledClass: 'disabled',
                        pageClass: 'page', linkClass: 'link',
                    },
                    icons: {
                        first: 'glyphicon glyphicon-step-backward',
                        prev: 'glyphicon glyphicon-chevron-left',
                        next: 'glyphicon glyphicon-chevron-right',
                        last: 'glyphicon glyphicon-step-forward',
                    },
                },
                sortOrder: [ { field: 'name', sortField: 'cpm_activities.id', direction: 'asc'} ],
                moreParams: {},
                isShowAssociatedUser: false,
                activityId: '',
                serviceId: '',
            }
        },
        methods: {
            openAssociatedUsersModal() {  this.isShowAssociatedUser = true;  },
            onCloseAssociatedUserModal() { this.isShowAssociatedUser = false; },
            onClickNew() {
                console.log('CpmActivityListTable onClickNew');
                let payload = { isShow: true,  data: {action: 'Add', data: null, index: ''}, };
                this.service_group = '';
                this.$store.dispatch('setCpmActivityShowModal', payload);
            },
            onPaginationData (paginationData) {
                this.$refs.cpmActivitypagination.setPaginationData(paginationData);
                this.$refs.cpmActivitypaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {  this.$refs.cpmActivityvuetable.changePage(page)  },
            onActions (data) {
                console.log('CpmActivityListTable onActions', data);
                let payload = { isShow: true,  data: data,   };
                if (data.action === 'Delete')
                {
                    let me = this;
                    this.$swal({
                        title: 'Are you sure?',
                        text: 'You will not be able to recover this service!',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'cancel',
                        confirmButtonClass: 'btn btn-success',
                        cancelButtonClass: 'btn btn-danger',
                        allowOutsideClick: false
                    }).then(function() {
                        me.$store.dispatch('deleteCpmActivityRequest', data.data)
                            .then((response) => {
                                console.log('deleteCpmActivityRequest fire events -> refreshCpmActivityTable');
                                me.$events.fire('refreshCpmActivityTable');
                            })
                            .catch((error) => {});
                        }, function (dismiss) {
                    });
                }
                else if (data.action === 'Edit')
                {
                    console.log('********** onActions edit data.data.service_group_activity=', data.data.service_group_activity);
                    this.service_group = data.data.service_group_activity? parseInt(data.data.service_group_activity.service_group_id): '';
                    this.$store.dispatch('setCpmActivityShowModal', payload);
                }
                else if (data.action === 'Add')
                {
                    // assign users and groups
                    this.activityId = data.data.id;
                    this.serviceId = data.data.service_id;
                    this.openAssociatedUsersModal();
                }
            },
        },
        events: {
            'cpm-activity-filter-set' (filterData) 
            {   this.moreParams = { filter: filterData };
                Vue.nextTick( () => {
                    if (this.$refs.cpmActivityvuetable)
                        this.$refs.cpmActivityvuetable.refresh()
                } )
            },
            'cpm-activity-filter-reset' () 
            {   this.moreParams = {}
                Vue.nextTick( () => {   if (this.$refs.cpmActivityvuetable)
                        this.$refs.cpmActivityvuetable.refresh()
                } )
            },
            'refreshCpmActivityTable'() 
                {    console.log('events -> refreshCpmActivityTable');
                    Vue.nextTick( () => {    if (this.$refs.cpmActivityvuetable)
                                         this.$refs.cpmActivityvuetable.refresh()
                                        } );
                }
        },
        watch: {   'perPage' (newVal, oldVal) 
                        {  this.$nextTick(function() 
                             {    if (this.$refs.cpmActivityvuetable)
                                  this.$refs.cpmActivityvuetable.refresh()
                             })
                        },
            'paginationComponent' (newVal, oldVal) 
                    {   this.$nextTick(function() 
                        {  this.$refs.cpmActivitypagination.setPaginationData(this.$refs.cpmActivityvuetable.tablePagination)
                        })
                    },
                },
    }
</script>
<style>
    .sql-statement {
        max-width: 200px;
        /*max-height: 100px;*/
        /*overflow-y: auto;*/
    }
</style>