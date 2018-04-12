<template>
    <div id="root-order-list-search">
        <div class="app-row" >
            <div class="panel panel-primary">
                <!-- Default panel contents -->
                <div class="panel-heading">
                    <a class="accordion-toggle" data-toggle="collapse"  href="#search-section">Search Criteria Section </a>
                    <span class="pull-right">
                        <button class="btn btn-success btn-sm" @click.prevent="doFilter">Search</button>
                        <button class="btn btn-warning  btn-sm" @click.prevent="resetFilter">Reset</button>
	               </span>
                </div>
                <div id="search-section" class="panel-collapse collapse in ">
                    <div class="row-content">
                        <div class="form-inline form-group form-group-sm input-group">
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4"><bs-input label="Ticket No" type="text"  :rows="1"  :maxlength="255" v-model="formSearchData.ticket_no"></bs-input></div>
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4"><bs-input label="ORDER_ID" type="text"  :rows="1"  :maxlength="255" v-model="formSearchData.ORDER_ID"></bs-input></div>
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4"><bs-input label="QUOTE_ID" type="text"  :rows="1"  :maxlength="255" v-model="formSearchData.QUOTE_ID"></bs-input></div>
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4"><div><label for="status">Status</label></div>
                                <Select clearable filterable v-model="formSearchData.ticketStatus"
                                        @on-change="onChangeStatus"
                                        placeholder="Please select a status..."
                                        style="width:180px"
                                >
                                    <Option v-for="item in statusOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                                </Select>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4"><div><label for="type">Type</label></div>
                                <Select clearable filterable v-model="formSearchData.ticketType"
                                        @on-change="onChangeType"
                                        placeholder="Please select a type..."
                                        style="width:180px"
                                >
                                    <Option v-for="item in typeOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                                </Select>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4"><div><label for="type">Created_by</label></div>
                                <Select clearable filterable v-model="formSearchData.createdby"
                                        @on-change="onChangeCreatedBy"
                                        placeholder="Select a user..."
                                        style="width:180px"
                                >
                                    <Option v-for="item in usersOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                                </Select>
                            </div>

                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4"><div><label for="type">Updated_by</label></div>
                                <Select clearable filterable v-model="formSearchData.updatedby"
                                        @on-change="onChangeUpdatedBy"
                                        placeholder="Select a user..."
                                        style="width:180px"
                                >
                                    <Option v-for="item in usersOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                                </Select>
                            </div>

                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4"><div><label for="type">Allocated_user</label></div>
                                <Select clearable filterable v-model="formSearchData.assigneduser"
                                        @on-change="onChangeAssignedUser"
                                        placeholder="Select a user..."
                                        style="width:180px"
                                >
                                    <Option v-for="item in usersOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                                </Select>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4"><div><label for="type">Managed_user</label></div>
                                <Select clearable filterable v-model="formSearchData.manageduser"
                                        @on-change="onChangeManagedUser"
                                        placeholder="Select a user..."
                                        style="width:180px"
                                >
                                    <Option v-for="item in usersOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                                </Select>
                            </div>


                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                <div><label for="status">Location</label></div>
                                <Select clearable filterable v-model="formSearchData.orderLocation"
                                        @on-change="onChangeLocation"
                                        placeholder="Please select a location..."
                                        style="width:180px"
                                >
                                    <Option v-for="item in locationOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                                </Select>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                                <div><label for="status">Created Date Range</label></div>
                                <Date-picker type="daterange" 
                                             format="dd/MM/yyyy"
                                             @on-change="onChangeDateRange"
                                             placeholder="Please input date range..."
                                             style="width: 200px"></Date-picker>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                                <div><label for="status">Updated Date Range</label></div>
                                <Date-picker type="daterange" 
                                             format="dd/MM/yyyy"
                                             @on-change="onChangeDateRangeUpdated"
                                             placeholder="Please input date range..."
                                             style="width: 200px"></Date-picker>
                            </div>
                             </div> </div>  </div> </div> </div> </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import input from 'vue-strap/src/Input'
    import * as api from '../../config';
//    import AppButtonSpinner from '../../AppButtonSpinner.vue';

    export default
     { computed: {  ...mapGetters({    }), ...mapState({  user: state => state.authUser, }),   },
       components: {     'bs-input': input, },   //   'app-button-spinner': AppButtonSpinner,
       created() 
         { console.log('/cs/Search.vue-CsTicketSearchSection vue created!');
           this.$store.dispatch('getticketstatustable')
           .then((response) =>
               { console.log('/cs/Search.vue-created getticketstatustable success=', response);
                 this.setOrderStatusOptions(response.data);
               })
            this.$store.dispatch('gettickettypetable')
            .then((response) =>
               { console.log('/cs/Search.vue-created gettickettypetable success=', response);
                 this.setTypeOptions(response.data);
               })
            
           .catch((error) => {  console.error('/cs/Search.vue--getAssignedLocationOptions error=', error);     });
            this.$store.dispatch('getAssignedLocationOptions')
                .then((response) => {
                    console.log('/cs/Search.vue-created getAssignedLocationOptions success=', response);
                    this.setAssignedLocationOptions(response.data);
                })
                .catch((error) => { console.error('/cs/Search.vue-getAssignedLocationOptions error=', error);  }); 


            
                 this.$store.dispatch('getuserlist') //---create dropdown for ticket types
                       .then((response) =>
                        { console.log('/cs/search.vue-created getuserlist success=', response);
                          //this.setOrderStatusOptions(response.data);
                          this.collectUserOptions(response.data);
                        })
                       .catch((error) => {  console.error('/cs/search.vue-getuserlist error=', error); });
            }, //---------------created finish
        data () 
        {    return {  apiStatusOptions: api.currentOrderStatusOptions,  minSearch: 1,
                       formSearchData: {    
                                            salesContact: '',  // salesperson
                                            salesOrderNumber: '', // UDF1
                                            superVisorUpdated: '',  // contact
                                            customerAddress: '',  // address
                                            users:'', customer: {code: '', name: ''}, 
                                            dateRange: ['',''],dateRangeUpdate: ['',''], 

                                            ticket_no:'',ORDER_ID:'',QUOTE_ID:'',ticketStatus:'',
                                    ticketType:'',createdby:'',updatedby:'',assigneduser:'',orderLocation:'',
                                       },
                        locationOptions: [], statusOptions: [],typeOptions: [],usersOptions:[],
                    }
        },
        methods: 
        {  onChangeDateRange(val) 
           {   console.log('/cs/Search.vue-onChangeDateRange val=',val);
                this.formSearchData.dateRange = val;
            },
            onChangeDateRangeUpdated(val) 
           {   console.log('/cs/Search.vue-onChangeDateRangeUpdate val=',val);
                this.formSearchData.dateRangeUpdate = val;
            },
           onChangeLocation(val) {  console.log('/cs/Search.vue--onChangeLocation val=',val); },
           onChangeStatus(val) { console.log('/cs/Search.vue--onChangeStatus val=',val);   },
           onChangeType(val) { console.log('/cs/Search.vue--onChangeType val=',val);   },
           onChangeCreatedBy(val) { console.log('/cs/Search.vue--onChangeCreatedBy val=',val);   },
           onChangeUpdatedBy(val) { console.log('/cs/Search.vue--onChangeUpdatedBy val=',val);   },
            onChangeAssignedUser(val) { console.log('/cs/Search.vue--onChangeUpdatedBy val=',val);   },
             onChangeManagedUser(val) { console.log('/cs/Search.vue--onChangeUpdatedBy val=',val);   },
           setOrderStatusOptions(statuses) 
            {   console.log('/cs/Search.vue--setOrderStatusOptions statuses=',statuses);
                let options = [];
                        for (let status in statuses) 
                         { options.push({value: statuses[status].id, label: statuses[status].STATUS});  }
                           this.statusOptions = options;
                           console.log('/cs/Search.vue--inside-setOrderStatusOptions statuses=',options);
            },
            setTypeOptions(types) 
            {   console.log('/cs/Search.vue--setTypeOptions types=',types);
                let options = [];
                        for (let type in types) 
                         { options.push({value: types[type].id, label: types[type].ticket_type});  }
                           this.typeOptions = options;
                           console.log('/cs/Search.vue-inside-setTypeOptions types=',options);
            },
            collectUserOptions(users) 
                {  console.log('/cs/search---collectUserOptions types=',users);
                    let options = [];
                     for (let users1 in users) 
                    { options.push({value: users[users1].id, label: users[users1].name});  }
                      this.usersOptions = options;
                },
            setAssignedLocationOptions(locations) 
            {   console.log('/cs/Search.vue--setAssignedLocationOptions locations=',locations);
                let options = [];
                for (let location in locations) {  options.push({value: locations[location].id, label: locations[location].name});    }
                this.locationOptions = options;
            },
            doFilter () 
            {   console.log('/cs/Search.vue-- doFilter');
                   //inside csticketlistable
                this.$events.fire('csticket-list-filter', this.formSearchData) 
            },
            resetFilter () 
            {   console.log('/cs/Search.vue--OrderListSearchSection resetFilter');
                this.resetFormData();
                // after reset, whether passing data or not  // actually is the same
                this.$events.fire('/cs/Search.vue--order-list-reset', this.formSearchData)
            },
            resetFormData() 
              {   this.formSearchData =  
                  { salesContact: '', salesOrderNumber: '',   superVisorUpdated: '',
                    customerAddress: '',   customer: {code: '', name: ''}, users:'',
                    dateRange: '',dateRangeUpdate:'', 
                    ticket_no:'',ORDER_ID:'',QUOTE_ID:'',ticketStatus:'',
                    ticketType:'',createdby:'',updatedby:'',assigneduser:'',orderLocation:'',manageduser:'',
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