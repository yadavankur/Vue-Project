<template>
    <div class="filter-bar">
        <form class="form-inline form-group-sm ">
            <div class="form-group">
                <label>Search for:</label>
                    <!--<Select clearable filterable v-model="formSearchData.location"-->
                            <!--@on-change="onChangeLocation"-->
                            <!--placeholder="Please select a location..."-->
                            <!--style="width:180px"-->
                    <!--&gt;-->
                        <!--<Option v-for="item in locationOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>-->
                    <!--</Select>-->
                <div class="cascade-service-group">
                    <Cascader v-model="cascade_location_service_group"
                              :data="cascadeServiceGroupOptions"
                              placeholder="Please select a location or service or group..."
                              @on-change="handleChange"
                              change-on-select
                              style="width:300px"
                    >
                    </Cascader>
                </div>
                <!--<input type="text" v-model="formSearchData.filterText" class="form-control" @keyup.enter="doFilter" placeholder="service or activity name">-->
                <bs-input placeholder="Serivce or Activity Name" type="text" :rows="1" :maxlength="255" v-model="formSearchData.filterText"></bs-input>
                <button class="btn btn-success btn-sm" @click.prevent="doFilter">Search</button>
                <button class="btn btn-warning btn-sm" @click.prevent="resetFilter">Reset</button>
                <button class="btn btn-primary btn-sm" title="New" @click.prevent="onClickNew">
                    <span class="glyphicon glyphicon-plus"></span>NEW
                </button>
            </div>
        </form>
    </div>
</template>

<script>
    import input from 'vue-strap/src/Input'
    import * as api from '../../../../config';
    export default {
        created() {
            console.log('CpmActivityCustomFilterBar Component created.');
//            // get location list
//            this.$store.dispatch('getAssignedLocationOptions')
//                .then((response) => {
//                    console.log('created getAssignedLocationOptions success=', response);
//                    this.setAssignedLocationOptions(response.data);
//                })
//                .catch((error) => {
//                    console.error('getAssignedLocationOptions error=', error);
//                });
            // this.getAssingedLocations();
            this.getCascadeServiceGroupOptions();
        },
        components: {
            'bs-input': input,
        },
        data () {
            return {
                formSearchData: {
                    filterText: '',
                    location: {id: '', name: ''},
                    service: {id: '', name: ''},
                    service_group: {id: '', name: ''},
                },
                locationOptions: [],
                cascadeServiceGroupOptions: [],
                cascade_location_service_group: [],
            }
        },
        methods: {
            getCascadeServiceGroupOptions() {
                this.$store.dispatch('getCpmCascadeServiceGroupOptions')
                    .then((response) => {
                        console.log('getCpmCascadeServiceGroupOptions success=', response);
                        this.cascadeServiceGroupOptions = response.data.locationServiceGroups;
                    })
                    .catch((error) => {
                        console.error('getCpmCascadeServiceGroupOptions error=', error);
                    });
            },
            handleChange (value, selectedData) {
                console.log('handleChange value=', value);
                console.log('handleChange selectedData=', selectedData);
                this.formSearchData.location = {id:'', name:''};
                this.formSearchData.service = {id:'', name:''};
                this.formSearchData.service_group = {id:'', name:''};
                if (selectedData.length > 0)
                {
                    this.formSearchData.location = {id: selectedData[0].value, name: selectedData[0].label};
                    if (selectedData.length > 1)
                        this.formSearchData.service = {id: selectedData[1].value, name: selectedData[1].label};
                    if (selectedData.length > 2)
                        this.formSearchData.service_group = {id: selectedData[selectedData.length-1].value, name: selectedData[selectedData.length-1].label};
                }
            },
            onChangeLocation(val) {
                console.log('onChangeLocation val=',val);
            },
//            setAssignedLocationOptions(locations) {
//                console.log('setAssignedLocationOptions locations=',locations);
//                let options = [];
//                for (let location in locations) {
//                    options.push({value: locations[location].id, label: locations[location].name});
//                }
//                this.locationOptions = options;
//            },
            doFilter () {
                //this.$events.fire('cpm-activity-filter-set', this.filterText)
                this.$events.fire('cpm-activity-filter-set', this.formSearchData);
            },
            resetFilter () {
                // this.filterText = '';
                this.formSearchData.filterText = '';
                this.formSearchData.location = {id:'', name:''};
                this.formSearchData.service = {id:'', name:''};
                this.formSearchData.service_group = {id:'', name:''};
                this.$events.fire('cpm-activity-filter-reset')
            },
            onClickNew() {
                console.log('CpmActivityCustomFilterBar onClickNew');
                this.$emit('onClickNew', {action: 'Add'});
            }
        }
    }
</script>
<style scoped>
    .cascade-service-group {
        display: inline-block;
    }
</style>