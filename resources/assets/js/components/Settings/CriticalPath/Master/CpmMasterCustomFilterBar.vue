<template>
    <div class="filter-bar">
        <form class="form-inline form-group-sm">
                <div class="pull-left">
                    <div class="pull-left">
                        <label >Search for:</label>
                    </div>
                    <div class="cascadeOptions">
                        <Cascader v-model="cascade_location_service"
                                  :data="cascadeServiceOptions"
                                  placeholder="Please select a service..."
                                  @on-change="handleChange"
                        >
                        </Cascader>
                    </div>
                </div>
                <div class="pull-right">
                    <!--<input type="text" v-model="filterText" class="form-control" @keyup.enter="doFilter" placeholder="name">-->
                    <bs-input placeholder="Activity Name" type="text" :rows="1" :maxlength="255" v-model="formSearchData.filterText"></bs-input>
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
    export default {
        components: {
            'bs-input': input,
        },
        props: {
            cascadeServiceOptions: {
                type: Array,
                default: [],
            }
        },
        data () {
            return {
                formSearchData: {
                    filterText: '',
                    service: '',
                },
                cascade_location_service: [],
            }
        },
        created() {
            // get location service list
        },
        methods: {
            handleChange (value, selectedData) {
                console.log('handleChange value=', value);
                console.log('handleChange selectedData=', selectedData);
                this.formSearchData.service = '';
                if (selectedData.length > 0)
                {
                    // service id
                    this.formSearchData.service = selectedData[selectedData.length-1].value;
                }
            },
            doFilter () {
                this.$events.fire('cpm-master-filter-set', this.formSearchData)
            },
            resetFilter () {
                this.formSearchData = {
                    filterText: '',
                    service: '',
                };
                this.cascade_location_service = [];
                this.$events.fire('cpm-master-filter-reset')
            },
            onClickNew() {
                console.log('CpmMasterCustomFilterBar onClickNew');
                this.$emit('onClickNew', {action: 'Add'});
            }
        }
    }
</script>
<style scoped>
    /*.pull-left {*/
        /*margin-bottom: 5px;*/
    /*}*/
    /*.filter-bar {*/
        /*padding: 5px;*/
    /*}*/
    .cascadeOptions {
        width: 250px !important;
        float: left;
        margin-right: 2px;
    }
    label {
        margin-top: 10px !important;
    }
</style>