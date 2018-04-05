<template>
    <div>
        <div class="filter-bar">
            <div class="pull-right">
                <form class="form-inline form-group-sm">
                    <label>Per Page:</label>
                    <select class="form-control dropdown" v-model="perPage">
                        <option :value="5">5</option><option :value="10">10</option>
                        <option :value="20">20</option><option :value="50">50</option>
                    </select>
                </form>
            </div>
        </div>

        <order-list-vue-table ref="orderVuetable"
                  :api-url="url"
                  :fields="fields"
                  :per-page="perPage"
                  :pagination-path="paginationPath"
                  :css="css.table"
                  :sort-order="sortOrder"
                  trackBy="QUOTE_ID"
                  :multi-sort="true"
                  :append-params="moreParams"
                  @vuetable:loading="onLoading"
                  @vuetable:loaded="onLoaded"
                  @vuetable:pagination-data="onPaginationData"
                  @vuetable:radiobox-toggled="onRadioChecked"
        ></order-list-vue-table>
        <div class="vuetable-pagination">
            <vuetable-pagination-info ref="orderListpaginationInfo"
                                      info-class="pagination-info"
            ></vuetable-pagination-info>
            <vuetable-pagination ref="orderListpagination"
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
    import * as api from '../../../config';
    import OrderListCustomFilterBar from './OrderListCustomFilterBar.vue'
    import moment from 'moment'
  // import OrderDetailsView1 from './OrderDetails.vue'

    export default 
    { beforeCreate() { console.log('/booking/orderlist/OrderListTable.vue-beforecreate!')   },
      computed: 
        { selectedRow() 
            {  if (this.$refs.orderVuetable)  {  return this.$refs.orderVuetable.selectedRadioRow()  }
                else   return '';
            }
        },
      props: {     },
      mounted() {  this.selectedRow = '';  },
      components: 
        {  'order-list-vue-table': Vuetable,  VuetablePagination,
            VuetablePaginationInfo,  'order-list-custom-filter-bar': OrderListCustomFilterBar,
            // 'order-d': OrderDetailsView1,
        },
      data () 
        { return {  url: '',   paginationPath: '',   search: '',    perPage: 5,
                    fields: 
                     [   { name: '__radiobox',  titleClass: 'text-center', dataClass: 'text-center',  },
                        { title: 'Location', name: 'QUOTE_NUM_PREF',  sortField: 'QUOTE_NUM_PREF',
                                   titleClass: 'text-center hidden-xs hidden-sm', dataClass: 'text-center hidden-xs hidden-sm',
                        },
                        {  title: 'Order No',   name: 'UDF1',      sortField: 'UDF1',  },
                        {  title: 'Customer Name', name: 'customer.CUST_NAME',
                           sortField: 'V_V6_CUSTOMER.CUST_NAME', titleClass: 'text-center hidden-xs hidden-sm',
                           dataClass: 'text-center hidden-xs hidden-sm',
                        },
                        { title: 'Tier Level', name: 'customer.tier.name',
                          titleClass: 'text-center hidden-xs hidden-sm', dataClass: 'text-center hidden-xs hidden-sm',
                        },
                        { title: 'Status',  name: 'status.DESCR', sortField: 'V_V6_STATUS.DESCR',
                           titleClass: 'text-center hidden-xs hidden-sm', dataClass: 'text-center hidden-xs hidden-sm',
                        },
                        { title: 'Enter Date', name: 'QUOTE_DATE',  callback: 'formatDate|DD/MMM/YYYY',
                        titleClass: 'text-center hidden-xs hidden-sm', dataClass: 'text-center hidden-xs hidden-sm',
                        },
                        {   title: 'Sales Rep',  name: 'sales_person.USER_NAME', titleClass: 'text-center hidden-xs hidden-sm',
                            dataClass: 'text-center hidden-xs hidden-sm',
                        },
                        {    title: 'Net Sell Price', name: 'TOTALAMOUNT', callback: 'formatPrice',
                             titleClass: 'text-center hidden-xs hidden-sm', dataClass: 'text-center hidden-xs hidden-sm',
                        },
                        {  title: 'Supervisor from V6', name: 'contact.CONTACT_NAME',
                           titleClass: 'text-center hidden-xs hidden-sm', dataClass: 'text-center hidden-xs hidden-sm',
                        },
                     ],
                   css: {
                    table: {   tableClass: 'table table-bordered table-striped table-hover',
                               ascendingIcon: 'glyphicon glyphicon-chevron-up',
                               descendingIcon: 'glyphicon glyphicon-chevron-down'
                            },
                    pagination: {   wrapperClass: 'pagination', activeClass: 'active',
                                    disabledClass: 'disabled', pageClass: 'page', linkClass: 'link',
                                },
                    icons: {  first: 'glyphicon glyphicon-step-backward',  prev: 'glyphicon glyphicon-chevron-left',
                              next: 'glyphicon glyphicon-chevron-right',  last: 'glyphicon glyphicon-step-forward',
                           },
                },
                sortOrder: [  { field: 'UDF1', sortField: 'UDF1', direction: 'asc'} ],
                moreParams: {}
            }
        },
        methods: 
        {
            onRadioChecked( isChecked, dataItem) 
            {   console.log('/booking/orderlist/OrderListTable.vue-onRadioChecked isChecked=', isChecked);
                console.log('/booking/orderlist/OrderListTable.vue-onRadioChecked dataItem=', dataItem);
                this.selectedRow = dataItem;
                this.$store.dispatch('setSelectedOrder', dataItem);
               
                this.$store.dispatch('getLastTicket'); //get the last ticket
                // this.$store.dispatch('displayCsTable', dataItem); //get tickets of this quote
                
                 this.$store.dispatch('getTicketsperQuote', dataItem); //get the last ticket
                
            },
            onPaginationData (paginationData) 
            {   this.$refs.orderListpagination.setPaginationData(paginationData);
                this.$refs.orderListpaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) 
            {   this.$refs.orderVuetable.changePage(page)  },
            onActions (data) 
            {   console.log('/booking/orderlist/OrderListTable.vue-orderListTable onActions', data);   },
            onLoading() 
            {   console.log('/booking/orderlist/OrderListTable.vue-loading... show your spinner here ie- onloading -call api via vue-resource') },
            onLoaded() 
            {  console.log('/booking/orderlist/OrderListTable.vue-loaded! .. hide your spinner here')    }
        },
        events: 
        { 'order-list-filter' (filterData) 
             { console.log('/booking/orderlist/OrderListTable.vue-OrderListTable order-list-set filterData=', filterData);
                this.url = api.currentOrderList;   this.selectedRow = '';
                this.$store.dispatch('setSelectedOrder', null);
                this.moreParams = {  filter: filterData   };
                Vue.nextTick( () => { if (this.$refs.orderVuetable)  this.$refs.orderVuetable.refresh();});
            },
            'order-list-reset' () 
            {   console.log('/booking/orderlist/OrderListTable.vue-OrderListTable order-list-set');
                this.url = api.currentOrderList;    this.selectedRow = '';    this.moreParams = {};
                this.$store.dispatch('setSelectedOrder', null);
                Vue.nextTick( () => this.$refs.orderVuetable.refresh() )
            },
            'refreshOrderListTable'() 
            {   console.log('/booking/orderlist/OrderListTable.vue-events -> refreshOrderListTable');
                Vue.nextTick( () => this.$refs.orderVuetable.refresh() );
            }
        },
        watch: 
        {      'perPage' (newVal, oldVal)
                {  this.$nextTick(function() {    this.$refs.orderVuetable.refresh()    }) },
               'paginationComponent' (newVal, oldVal) 
                {    this.$nextTick(function() { this.$refs.orderListpagination.setPaginationData(this.$refs.orderVuetable.tablePagination) })  },
               selectedRow() 
                 {  console.log('/booking/orderlist/OrderListTable.vue-watch +++++++ selectedRow() = ', this.selectedRow);
                 }
        },
    }
</script>
<style scoped>
    .filter-bar {   padding: 10px;   }
    .form-control {   height: 30px !important;  }
    .pull-right {   margin-bottom: 10px;  }
</style>
