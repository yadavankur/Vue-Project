<template>
    <div>
        <div class="filter-bar">
            <div class="pull-right">
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
        <booking-list-vue-table ref="bookingVuetable"
                              :api-url="url"
                              :fields="fields"
                              :per-page="perPage"
                              :pagination-path="paginationPath"
                              :css="css.table"
                              :sort-order="sortOrder"
                              trackBy="id"
                              :multi-sort="true"
                              :append-params="moreParams"
                              @vuetable:pagination-data="onPaginationData"
                              @vuetable:radiobox-toggled="onRadioChecked"
        ></booking-list-vue-table>
        <div class="vuetable-pagination">
            <vuetable-pagination-info ref="bookingListpaginationInfo"
                                      info-class="pagination-info"
            ></vuetable-pagination-info>
            <vuetable-pagination ref="bookingListpagination"
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
    import * as api from '../../../../config';
    export default {
        computed: {
        },
        props: {
        },
        mounted() {
            this.selectedRow = '';
        },
        components: {
            'booking-list-vue-table': Vuetable,
            VuetablePagination,
            VuetablePaginationInfo,
        },
        data () {
            return {
                selectedRow: '',
                url: '',
                paginationPath: '',
                search: '',
                perPage: 5,
                fields: [
                    {
                        name: '__radiobox',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                    },
                    {
                        title: 'Location',
                        name: 'order.QUOTE_NUM_PREF',
                        sortField: 'V_V6_QUOTE.QUOTE_NUM_PREF',
                    },
                    {
                        title: 'Sales Rep',
                        name: 'order.sales_person.USER_NAME',
                    },
                    {
                        title: 'SO NO',
                        name: 'order_id',
                        sortField: 'order_id',
                    },
                    {
                        title: 'Quote Number',
                        name: 'order.QUOTE_NUM',
                        sortField: 'V_V6_QUOTE.QUOTE_NUM',
                    },
                    {
                        title: 'Confirmed Date',
                        name: 'confirmed_date',
                        callback: 'formatDate|DD/MMM/YYYY'
                    },
                    {
                        title: 'Delivery Date',
                        name: 'agreed_date',
                        sortField: 'agreed_date',
                        callback: 'formatDate|DD/MMM/YYYY'
                    },
                    {
                        title: 'Supervisor',
                        name: 'order.contact',
                        callback: 'getSupervisorInfo',
                        titleClass: 'text-center hidden-xs hidden-sm',
                        dataClass: 'text-center hidden-xs hidden-sm',
                    },
                    {
                        title: 'Customer Name',
                        name: 'order.customer.CUST_NAME',
                    },
                    {
                        title: 'Address',
                        name: 'order.deliver_address.ADDR_1',
                        titleClass: 'text-center hidden-xs hidden-sm',
                        dataClass: 'text-center hidden-xs hidden-sm',
                    },
                    {
                        title: 'City',
                        name: 'order.deliver_address.ADDR_3',
                        titleClass: 'text-center hidden-xs hidden-sm',
                        dataClass: 'text-center hidden-xs hidden-sm',
                    },
//                    {
//                        title: 'Email',
//                        name: 'order.customer.EMAIL_ADDR',
//                        titleClass: 'text-center hidden-xs hidden-sm',
//                        dataClass: 'text-center hidden-xs hidden-sm',
//                    },
                    {
                        title: 'Confirmed?',
                        name: 'confirmed',
                        titleClass: 'text-center hidden-xs hidden-sm',
                        dataClass: 'text-center hidden-xs hidden-sm',
                        callback: 'getConfirmedStatus'
                    },
                ],
                css: {
                    table: {
                        tableClass: 'table table-bordered table-striped table-hover',
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
                    { field: 'UDF1', sortField: 'UDF1', direction: 'asc'}
                ],
                moreParams: {}
            }
        },
        methods: {
            getConfirmedStatus(status) {
                let element = '';
                switch (parseInt(status))
                {
                    case 0:
                        element = '<span class="label label-danger" title="Unconfirmed"><span class="glyphicon glyphicon-warning-sign"></span></span>';
                        break;
                    case 1:
                        element = '<span class="label label-success" title="Confirmed"><span class="glyphicon glyphicon-ok-sign"></span></span>';
                        break;
                }
                return element;
            },
            getSupervisorInfo(contact)
            {
                console.log('getSupervisorInfo contact=', contact);
                return contact.CONTACT_NAME + ' ' + (contact.PHONE? contact.PHONE : contact.MOBILE);
            },
            onRadioChecked( isChecked, dataItem) {
                console.log('onRadioChecked isChecked=', isChecked);
                console.log('%%%%%%%%%%%% onRadioChecked dataItem=', dataItem);
                this.selectedRow = dataItem;
                this.$store.dispatch('setConfirmationSelectedOrder', dataItem);
                // cause when firing the following event
                // the associated job table maybe is not created yet
                // thus causing no refresh property found error
                // this.$events.fire('confirmation-associated-jobs-list-filter-reset');
            },
            onPaginationData (paginationData) {
                this.$refs.bookingListpagination.setPaginationData(paginationData);
                this.$refs.bookingListpaginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                this.$refs.bookingVuetable.changePage(page)
            },
            onActions (data) {
                console.log('bookingListTable onActions', data);
            },
        },
        events: {
            'booking-list-filter' (filterData) {
                console.log('BookingListTable booking-list-set filterData=', filterData);
                this.url = api.currentConfirmationList;
                this.selectedRow = '';
                this.$store.dispatch('setConfirmationSelectedOrder', null);
                this.moreParams = {
                    filter: filterData
                };
                Vue.nextTick( () => {
                    if (this.$refs.bookingVuetable)
                        this.$refs.bookingVuetable.refresh();
                });
            },
            'booking-list-reset' () {
                console.log('BookingListTable booking-list-set');
                this.url = api.currentConfirmationList;
                this.selectedRow = '';
                this.moreParams = {};
                this.$store.dispatch('setConfirmationSelectedOrder', null);
                Vue.nextTick( () => {
                    if (this.$refs.bookingVuetable)
                        this.$refs.bookingVuetable.refresh()
                });
            },
            'refreshBookingListTable'() {
                console.log('events -> refreshBookingListTable');
                //this.$store.dispatch('setConfirmationSelectedOrder', null);
                Vue.nextTick( () => {
                    if (this.$refs.bookingVuetable)
                        this.$refs.bookingVuetable.refresh()
                });
            }
        },
        watch: {
            'perPage' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.bookingVuetable.refresh()
                })
            },
            'paginationComponent' (newVal, oldVal) {
                this.$nextTick(function() {
                    this.$refs.bookingListpagination.setPaginationData(this.$refs.bookingVuetable.tablePagination)
                })
            },
        },
    }
</script>
<style scoped>
    .form-control {
        height: 30px !important;
    }
    .filter-bar {
        padding: 10px;
    }
    .pull-right {
        margin-bottom: 10px;
    }
</style>
