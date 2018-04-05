<template>
<div id="root-order-list-search">
    <div class="app-row" >
        <div class="panel panel-primary"> <!-- Default panel contents -->
            <div class="panel-heading">
                <a class="accordion-toggle" data-toggle="collapse"  href="#search-section"> Search Criteria Section </a>
                   <span class="pull-right">
                        <button class="btn btn-success btn-sm" @click.prevent="doFilter">Search</button>
                        <button class="btn btn-warning  btn-sm" @click.prevent="resetFilter">Reset</button>
	               </span>
            </div><!-- class="panel-heading" finish-->
            <div id="search-section" class="panel-collapse collapse in ">
                <div class="row-content">
                    <div class="form-inline form-group form-group-sm input-group">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4"><bs-input label="Dowell Sales Contact" type="text"  :rows="1"  :maxlength="255" v-model="formSearchData.salesContact"></bs-input></div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4"><bs-input label="Sales Order Number" type="text" :rows="1"  :maxlength="255" v-model="formSearchData.salesOrderNumber"></bs-input></div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4"><bs-input label="Supervisor Updated" type="text" :rows="1" :maxlength="255" v-model="formSearchData.superVisorUpdated"></bs-input></div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4"><bs-input label="Customer Address" type="text" :rows="1" :maxlength="255" v-model="formSearchData.customerAddress"></bs-input></div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4"><bs-input label="Customer Name" type="text" :rows="1" :maxlength="255" v-model="formSearchData.customer.name"></bs-input></div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4"><bs-input label="Customer Code" type="text" :rows="1" :maxlength="10" v-model="formSearchData.customer.code"></bs-input></div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                          <div><label for="status">Status</label></div>
                          <Select clearable filterable v-model="formSearchData.orderStatus" @on-change="onChangeStatus" placeholder="Please select a status..." style="width:180px" >
                                <Option v-for="item in statusOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                          </Select>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                          <div><label for="status">Location</label></div>
                          <Select clearable filterable v-model="formSearchData.orderLocation" @on-change="onChangeLocation" placeholder="Please select a location..." style="width:180px" >
                                 <Option v-for="item in locationOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                          </Select>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                            <div><label for="status">Delivery Date Range</label></div>
                            <Date-picker type="daterange"  format="dd/MM/yyyy" @on-change="onChangeDateRange" placeholder="Please input date range..."  style="width: 200px"></Date-picker>
                        </div> 
                    </div> <!--class="form-inline form-group form-group-sm input-group" -->
                </div>  <!--class="row-content" --> 
            </div> <!-- id="search-section" class="panel-collapse collapse in " -->
        </div> <!-- class="panel panel-primary" contains both search section heading and text boxes-->
    </div> <!-- class="app-row" -->
</div><!-- id="root-order-list-search" -->
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import input from 'vue-strap/src/Input'
    import * as api from './../../config';
//    import AppButtonSpinner from '../../AppButtonSpinner.vue';

 export default
    { computed: {  ...mapGetters({    }), ...mapState({  user: state => state.authUser, }),   },
      components: {     'bs-input': input, },   //   'app-button-spinner': AppButtonSpinner,
      created() 
         { console.log('/O2/OListSearchSection.vue-OrderListSearchSection vue created!');
           this.$store.dispatch('getOrderStatusOptions')
           .then((response) =>
               { console.log('/Ol2/OListSearchSection.vue-created getOrderStatusOptions success=', response);
                 this.setOrderStatusOptions(response.data);
               })
           .catch((error) => {  console.error('/new3/OListSearchSection.vue--getAssignedLocationOptions error=', error);     });
            this.$store.dispatch('getAssignedLocationOptions')
                .then((response) => {
                    console.log('/Ol2/OListSearchSection.vue--created getAssignedLocationOptions success=', response);
                    this.setAssignedLocationOptions(response.data);
                })
                .catch((error) => {
                    console.error('/new3/OListSearchSection.vue--getAssignedLocationOptions error=', error);       });
        },
        data () 
        {    return {  apiStatusOptions: api.currentOrderStatusOptions,  minSearch: 1,
                       formSearchData: {    salesContact: '',  // salesperson
                                            salesOrderNumber: '', // UDF1
                                            superVisorUpdated: '',  // contact
                                            customerAddress: '',  // address
                                            orderStatus: '', customer: {code: '', name: ''}, orderLocation: '',
                                            dateRange: ['',''],
                                       },
                        locationOptions: [], statusOptions: [],
                    }
        },
        methods: 
        {  onChangeDateRange(val) 
            {   console.log('/Ol2/OListSearchSection.vue--onChangeDateRange val=',val);
                this.formSearchData.dateRange = val;
            },
           onChangeLocation(val) {  console.log('/Ol2/OListSearchSection.vue--onChangeLocation val=',val); },
           onChangeStatus(val) { console.log('/Ol2/OListSearchSection.vue--onStatusLocation val=',val);  },
           setOrderStatusOptions(statuses) 
            {   console.log('/Ol2/OListSearchSection.vue--setOrderStatusOptions statuses=',statuses);
                let options = [];
                for (let status in statuses) {
                    options.push({value: statuses[status].DESCR, label: statuses[status].DESCR});
                }
                this.statusOptions = options;
            },
          setAssignedLocationOptions(locations) 
            {   console.log('/Ol2/OListSearchSection.vue--setAssignedLocationOptions locations=',locations);
                let options = [];
                for (let location in locations) {  options.push({value: locations[location].id, label: locations[location].name});    }
                this.locationOptions = options;
            },
          doFilter () 
            {   console.log('/Ol2/OListSearchSection.vue--OrderListSearchSection doFilter');
                   //     this.isSubmitted = true;
                this.$events.fire('order-list-filter', this.formSearchData)
            },
          resetFilter () 
            {   console.log('/Ol2/OListSearchSection.vue--OrderListSearchSection resetFilter');
                this.resetFormData();
                // after reset, whether passing data or not---  // actually is the same
                this.$events.fire('/Ol2/OListSearchSection.vue--order-list-reset', this.formSearchData)
            },
          resetFormData() 
              {     this.formSearchData =  
                  { salesContact: '', salesOrderNumber: '',   superVisorUpdated: '',
                    customerAddress: '',  orderStatus: '', customer: {code: '', name: ''},
                    dateRange: ['',''],
                   };
              }
        }
    }
</script>
<style scoped>
    .pull-right
    {       padding-right: 20px;    }
    .label {   margin-bottom: 0px !important;    }
    .ivu-select-input {   color: #0060af !important;   }
</style>