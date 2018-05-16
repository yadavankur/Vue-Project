<template>
    <div>
        <div>
            <div class="pull-left">
                <permission-custom-filter-bar @onClickNew="onClickNew"></permission-custom-filter-bar>
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

        <vuetable ref="permissionvuetable"
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
                  @vuetable:radiobox-toggled="onRadioChecked"
        ></vuetable>



        <div class="vuetable-pagination">
            <vuetable-pagination-info ref="permissionpaginationInfo"
                                      info-class="pagination-info"
            ></vuetable-pagination-info>
            <vuetable-pagination ref="permissionpagination"
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
    import * as api from './../../config';
    import PermissionCustomActions from './CsTicketCustomActions.vue'
    import PermissionCustomFilterBar from './CsTicketCustomFilterBar.vue'


    Vue.component('permission-custom-actions', PermissionCustomActions);

    export default 
    {   computed: { url(){  // return api.currentPermissionNodes; 
                             return api.currentTicketNodes; 
                         }  
                  },
        props:    {    },
        components: { Vuetable, VuetablePagination,  VuetablePaginationInfo,
                     'permission-custom-filter-bar': PermissionCustomFilterBar,
                   },
        created() {             this.$store.dispatch('getticketcnstatustable'); //to get credit note status--used /type2A/crud   
                               this.$store.dispatch('getticketerrortypetable');
                               this.$store.dispatch('getuserlist');

        },
        data () 
           { return {   paginationPath: '',
                        search: '',
                        perPage: 5,
                        fields: 
                           [   { name: '__radiobox',  titleClass: 'text-center', dataClass: 'text-center',  },
                               {  title: 'TicketNo', name: 'ticket_no',sortField: 'ticket_no',  titleClass: 'text-right',  dataClass: 'text-right' },
                                //{  title: 'Type', name: 'ticket_type_id',sortField: 'ticket_type_id',     },
                                {  title: 'Type', name: 'ttype.ticket_type',sortField: 'ticket_type_id',     },
                               // {  title: 'QuoteID', name: 'QUOTE_ID',sortField: 'QUOTE_ID',     },
                                {  title: 'OrderID', name: 'ORDER_ID',sortField: 'ORDER_ID',     },
                                {  title: 'Status', name: 'tstatus.STATUS',sortField: 'status',     },
                                {  title: 'Location', name: 'location.name',    },
                                {  title: 'CONTACT', name: 'CONTACT_PERSON',     },
                               // {  title: 'Comments', name: 'comment',   },
                                {  title: 'AllocatedUser', name: 'auserid.name',sortField: 'user_id',     },
                                 {  title: 'ManagedUser', name: 'buserid.name',sortField: 'user_id',     },
                                {  title: 'Created_by', name: 'created_by.name',sortField: 'user_id',     },
                                {  title: 'Updated_by', name: 'updated_by.name',sortField: 'user_id',},
                                {  title: 'Updated_At', name: 'updated_at',sortField: 'updated_at',     },
                             //   {  title: 'Created_At', name: 'created_at',sortField: 'created_at',     },
                                
                                 
                              { name: '__component:permission-custom-actions', title: 'Actions',  titleClass: 'text-center', dataClass: 'text-center'  }
                           ],
                        css: {  table: {   tableClass: 'table table-bordered table-striped table-hover',
                                          ascendingIcon: 'glyphicon glyphicon-chevron-up',
                                          descendingIcon: 'glyphicon glyphicon-chevron-down'
                                      },
                                pagination: {   wrapperClass: 'pagination',
                                                activeClass: 'active',
                                                disabledClass: 'disabled',
                                                pageClass: 'page',
                                                linkClass: 'link',
                                            },
                                icons:  {    first: 'glyphicon glyphicon-step-backward',
                                             prev: 'glyphicon glyphicon-chevron-left',
                                             next: 'glyphicon glyphicon-chevron-right',
                                             last: 'glyphicon glyphicon-step-forward',
                                        },
                             },
                sortOrder: [ { //field: 'name', sortField: 'name', direction: 'asc'
                                //field: 'id', sortField: 'id', direction: 'asc'
                                field: 'updated_at', sortField: 'updated_at', direction: 'dsc'
                             }  ],
                moreParams: {}
            }
        },
        methods: 
        {             
            onClickNew() 
            {   console.log('/cs/list-- onClickNew');
                let formData = {   ticket_no: '',ticket_type_id: '',QUOTE_ID: '',ORDER_ID: '',
                                   location_id: '',
                                   name: '',  title: '',  id: '', locationOptions : this.locationOptions,
                              // cascadeLocationOptions: this.cascadeLocationOptions,
                               
                               };
                let payload = {  isShow: true, data: {action: 'Add', data: formData, index: '',  }, };
                this.$store.dispatch('setCsTicketShowPopup', payload);
            },
            onRadioChecked( isChecked, dataItem) 
            {   console.log('/cs/list---onRadioChecked isChecked=', isChecked);
                console.log('/cs/list---onRadioChecked dataItem=', dataItem); 
                if(dataItem != null)
                this.$store.dispatch('setSelectedTicket', dataItem);
            },
            onPaginationData (paginationData) 
            {   this.$refs.permissionpagination.setPaginationData(paginationData);
                this.$refs.permissionpaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {  this.$refs.permissionvuetable.changePage(page)   },
            onActions (data) 
            {   console.log('/cs/list-- onActions', data);
                let payload = { isShow: true,  data: data,  };
                if (data.action === 'Delete')
                {   let swal = this.$swal;
                    let me = this;
                    this.$swal(
                        { title: 'Are you sure?', text: 'You will not be able to recover this cstkt!',
                          type: 'warning',  showCancelButton: true,
                          confirmButtonColor: '#3085d6',  cancelButtonColor: '#d33',
                          confirmButtonText: 'Yes', cancelButtonText: 'cancel',
                          confirmButtonClass: 'btn btn-success',  cancelButtonClass: 'btn btn-danger',
                          allowOutsideClick: false
                         }).then(function() 
                         {  me.$store.dispatch('deletecstkt', data.data)
                            .then((response) => { console.log('delete cstkt -> refresh Table');
                                                  me.$events.fire('refreshcsticket');
                                                })
                            .catch((error) => {});
                          }, function (dismiss) {   });
                    return;
                }
                this.$store.dispatch('setCsTicketShowPopup', payload); //---if not delete then edit and add----show popup
            },
        },
        events: {  'permission-filter-set' (filterText)
                        {  this.moreParams = { filter: filterText  }
                           Vue.nextTick( () => this.$refs.permissionvuetable.refresh() )
                        },
                   'permission-filter-reset' () 
                        { this.moreParams = {}
                           Vue.nextTick( () => this.$refs.permissionvuetable.refresh() )
                        },
                   'refreshcsticket'() 
                       {   console.log('/cs/listtable---events -> refreshcsticket');
                           Vue.nextTick( () => this.$refs.permissionvuetable.refresh() );
                        },
                    'csticket-list-filter' (filterData) 
                        {   console.log('/cs/csticketlistable.vue---csticket-list-set filterData=', filterData);
                            this.moreParams = {   filter: filterData };  
                            Vue.nextTick( () => this.$refs.permissionvuetable.refresh() ) 
                        },
                },
        watch: 
        { 'perPage' (newVal, oldVal) 
            { this.$nextTick(function() { this.$refs.permissionvuetable.refresh() }) },
           'paginationComponent' (newVal, oldVal) 
                  {   console.log('manoj in pagination comp');
                      this.$nextTick(function() 
                       { this.$refs.permissionpagination.setPaginationData(this.$refs.permissionvuetable.tablePagination) })
                  },
           url() {   console.log('/cs/list--watch +++++++ url() = ', this.url);
                      this.$nextTick(function() {  this.$refs.permissionvuetable.refresh() })
                    }
        },
    }
</script>
<style scoped>
    .pagination {  margin: 0;  float: right; }
    .pagination a.page 
    {   border: 1px solid lightgray;   border-radius: 3px; padding: 5px 10px;  margin-right: 2px; }
    .pagination a.page.active 
    {   color: white; background-color: #337ab7; border: 1px solid lightgray; border-radius: 3px;  padding: 5px 10px;
        margin-right: 2px;
    }
    .pagination a.btn-nav 
    {   border: 1px solid lightgray;
        border-radius: 3px;
        padding: 5px 7px;
        margin-right: 2px;
    }
    .pagination a.btn-nav.disabled 
    {   color: lightgray;
        border: 1px solid lightgray;
        border-radius: 3px;
        padding: 5px 7px;
        margin-right: 2px;
        cursor: not-allowed;
    }
    .pagination-info {  float: left;}
    .filter-bar {  padding: 10px;  }
</style>