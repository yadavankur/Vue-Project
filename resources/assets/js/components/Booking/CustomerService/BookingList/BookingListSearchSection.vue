<template>
    <div id="root-cs-booking-list-search">
        <div class="app-row" >
            <div class="panel panel-primary">
                <!-- Default panel contents -->
                <div class="panel-heading">
                    <a class="accordion-toggle" data-toggle="collapse"  href="#search-section">
                        Search Criteria Section
                    </a>
                    <span class="pull-right">
                        <button class="btn btn-success btn-sm" @click.prevent="doFilter">Search</button>
                        <button class="btn btn-warning  btn-sm" @click.prevent="resetFilter">Reset</button>
	               </span>
                </div>
                <div id="search-section" class="panel-collapse collapse in ">
                    <div class="row-content">
                        <div class="form-inline form-group form-group-sm input-group">
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <bs-input label="Dowell Sales Rep Name" type="text"  :rows="1"  :maxlength="255" v-model="formSearchData.salesContact.name"></bs-input>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <bs-input label="Dowell Sales Rep#" type="text"  :rows="1"  :maxlength="255" v-model="formSearchData.salesContact.code"></bs-input>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <bs-input label="Supervisor Name" type="text" :rows="1" :maxlength="255" v-model="formSearchData.superVisorUpdated.name"></bs-input>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <bs-input label="Supervisor Phone" type="text" :rows="1" :maxlength="255" v-model="formSearchData.superVisorUpdated.phone"></bs-input>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <bs-input label="Sales Order Number" type="text" :rows="1"  :maxlength="255" v-model="formSearchData.salesOrderNumber"></bs-input>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <bs-input label="Customer Address" type="text" :rows="1" :maxlength="255" v-model="formSearchData.customerAddress"></bs-input>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <bs-input label="Customer Name" type="text" :rows="1" :maxlength="255" v-model="formSearchData.customer.name"></bs-input>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <bs-input label="Customer Code" type="text" :rows="1" :maxlength="10" v-model="formSearchData.customer.code"></bs-input>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <!--<bs-input label="Order Status" type="text" :rows="1" :maxlength="255" v-model="formSearchData.orderStatus"></bs-input>-->
                                <div><label for="status">Status</label></div>
                                <Select clearable filterable v-model="formSearchData.orderStatus"
                                        @on-change="onChangeStatus"
                                        placeholder="Please select a status..."
                                        style="width:180px"
                                >
                                    <Option v-for="item in statusOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                                </Select>
                            </div>

                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div><label for="status">Location</label></div>
                                <Select clearable filterable v-model="formSearchData.orderLocation"
                                        @on-change="onChangeLocation"
                                        placeholder="Please select a location..."
                                        style="width:180px"
                                >
                                    <Option v-for="item in locationOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                                </Select>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div><label for="status">Delivery Date Range</label></div>
                                <Date-picker type="daterange"
                                             placement="bottom-end"
                                             format="dd/MM/yyyy"
                                             @on-change="onChangeDateRange"
                                             placeholder="Please input date range..."
                                             style="width: 200px"></Date-picker>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import input from 'vue-strap/src/Input'
    import * as api from '../../../../config';

    export default 
    {   computed: {...mapGetters({   }),  ...mapState({  user: state => state.authUser,}),  },
        components: {    'bs-input': input, },
        created() {
            console.log('/booking/customerservice/bookinglistsearchsectin.vue-CSBookingListSearchSection vue created!');
            this.$store.dispatch('getOrderStatusOptions')
                .then((response) => {
                    console.log('/booking/customerservice/bookinglistsearchsectin.vue-created getOrderStatusOptions success=', response);
                    this.setOrderStatusOptions(response.data);
                })
                .catch((error) => {
                    console.error('/booking/customerservice/bookinglistsearchsectin.vue-getAssignedLocationOptions error=', error);
                });
            this.$store.dispatch('getAssignedLocationOptions')
                .then((response) => {
                    console.log('/booking/customerservice/bookinglistsearchsectin.vue-created getAssignedLocationOptions success=', response);
                    this.setAssignedLocationOptions(response.data);
                })
                .catch((error) => {
                    console.error('/booking/customerservice/bookinglistsearchsectin.vue-getAssignedLocationOptions error=', error);
                });
        },
        data () {
            return {
                formSearchData: {
                    salesContact: {code: '', name: ''},  // salesperson
                    salesOrderNumber: '', // UDF1
                    superVisorUpdated: {phone: '', name: ''},  // contact
                    customerAddress: '',  // address
                    orderStatus: '',
                    customer: {code: '', name: ''},
                    orderLocation: '',
                    dateRange: ['',''],
                    confirmedStatus: '',
                },
                locationOptions: [],
                statusOptions: [],
            }
        },
        methods: {
            onChangeDateRange(val) {
                console.log('/booking/customerservice/bookinglistsearchsectin.vue-onChangeDateRange val=',val);
                this.formSearchData.dateRange = val;
            },
            onChangeLocation(val) {
                console.log('/booking/customerservice/bookinglistsearchsectin.vue-onChangeLocation val=',val);
            },
            onChangeStatus(val) {
                console.log('/booking/customerservice/bookinglistsearchsectin.vue-onStatusLocation val=',val);
            },
            setOrderStatusOptions(statuses) {
                console.log('/booking/customerservice/bookinglistsearchsectin.vue-setOrderStatusOptions statuses=',statuses);
                let options = [];
                for (let status in statuses) {
                    options.push({value: statuses[status].DESCR, label: statuses[status].DESCR});
                }
                this.statusOptions = options;
            },
            setAssignedLocationOptions(locations) {
                console.log('/booking/customerservice/bookinglistsearchsectin.vue-/setAssignedLocationOptions locations=',locations);
                let options = [];
                for (let location in locations) {
                    options.push({value: locations[location].id, label: locations[location].name});
                }
                this.locationOptions = options;
            },
            doFilter () {
                console.log('/booking/customerservice/bookinglistsearchsectin.vue-CSBookingListSearchSection doFilter');
                this.$events.fire('cs-booking-list-filter', this.formSearchData)
            },
            resetFilter () {
                console.log('/booking/customerservice/bookinglistsearchsectin.vue-CSBookingListSearchSection resetFilter');
                this.resetFormData();
                // after reset, whether passing data or not
                // actually is the same
                this.$events.fire('cs-booking-list-reset', this.formSearchData)
            },
            resetFormData() {
                this.formSearchData =  {
                    salesContact: {code: '', name: ''},
                    salesOrderNumber: '',
                    superVisorUpdated: {phone: '', name: ''},
                    customerAddress: '',
                    orderStatus: '',
                    customer: {code: '', name: ''},
                    dateRange: ['',''],
                    confirmedStatus: '',
                };
            }
        }
    }
</script>
<style scoped>
    /*.panel-primary > .panel-heading {*/
        /*color: white;*/
        /*background-color: #0a5b9e;*/
        /*border-color: #0a5b9e;*/
        /*height: 40px;*/
    /*}*/
    /*.panel-heading a {*/
        /*color: white;*/
    /*}*/
    /*.panel-heading .accordion-toggle:after {*/
        /*!* symbol for "opening" panels *!*/
        /*font-family: 'Glyphicons Halflings';*/
        /*content: "\e114";*/
        /*float: right;*/
        /*color: white;*/
    /*}*/
    /*.panel-heading .accordion-toggle.collapsed:after {*/
        /*!* symbol for "collapsed" panels *!*/
        /*content: "\e080";*/
    /*}*/
    /*.row-content {*/
        /*padding: 10px;*/
    /*}*/
    .pull-right
    {
        padding-right: 20px;
    }
    .label {
        margin-bottom: 0px !important;
    }
    .ivu-select-input {
        color: #0060af !important;
    }
</style>