<template>
    <div class="filter-bar">
        <form class="form-inline form-group-sm">
            <div class="form-group">
                <label >Search for:</label>
                <div class="cascade-service-group">
                    <Cascader v-model="cascade_location_service"
                              :data="cascadeServiceOptions"
                              placeholder="Please select a service..."
                              @on-change="handleChange"
                              style="width:220px"
                    >
                    </Cascader>
                </div>
                <input type="text" v-model="formSearchData.filterText" class="form-control" @keyup.enter="doFilter" placeholder="name">
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
    export default {
        created() {
            console.log('CpmServiceGroupCustomFilterBar Component created.');
            this.getCascadeLocationServices();
        },
        props: {
        },
        data () {
            return {
                formSearchData: {
                    filterText: '',
                    location: {id:'', name:''},
                    service: {id:'', name:''},
                },
                cascadeServiceOptions: [],
                cascade_location_service: [],
            }
        },
        methods: {
            handleChange (value, selectedData) {
                console.log('handleChange value=', value);
                console.log('handleChange selectedData=', selectedData);
                this.formSearchData.location = {id:'', name:''};
                this.formSearchData.service = {id:'', name:''};
                if (selectedData.length > 0)
                {
                    this.formSearchData.location = {id: selectedData[0].value, name: selectedData[0].label};
                    this.formSearchData.service = {id: selectedData[selectedData.length-1].value, name: selectedData[selectedData.length-1].label};
                }
            },
            doFilter () {
                this.$events.fire('cpm-service-group-filter-set', this.formSearchData)
            },
            resetFilter () {
                this.formSearchData = {
                    filterText: '',
                    location: {id:'', name:''},
                    service: {id:'', name:''},
                };
                this.$events.fire('cpm-service-group-filter-reset')
            },
            onClickNew() {
                console.log('CpmServiceGroupCustomFilterBar onClickNew');
                this.$emit('onClickNew');
            }
        }
    }
</script>
<style scoped>
    .cascade-service-group {
        display: inline-block;
    }
</style>