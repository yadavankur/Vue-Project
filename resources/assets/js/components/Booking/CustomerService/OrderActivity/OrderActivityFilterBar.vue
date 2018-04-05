<template>
    <div class="filter-bar">
        <form class="form-inline form-group-sm">
            <div class="pull-left">
                <label >Search for:</label>
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
                <bs-input placeholder="activity name ..." type="text" :rows="1" :maxlength="255" v-model="formSearchData.filterText"></bs-input>
                <button class="btn btn-success btn-sm"
                        @click.prevent="doFilter">Search
                </button>
                <button class="btn btn-warning btn-sm"
                        @click.prevent="resetFilter">Reset
                </button>
                <button class="btn btn-primary btn-sm"
                        :disabled="formSearchData.service.id? false: true"
                        @click.prevent="openSimulator">CPM Simulation
                </button>
            </div>
        </form>
    </div>
</template>

<script>
    import { mapGetters, mapState, mapActions} from 'vuex'
    import input from 'vue-strap/src/Input'

    export default {
        computed: {
            ...mapState({
                user: state => state.authUser,
            }),
        },
        components: {
            'bs-input': input,
        },
        props: {
            cascadeServiceGroupOptions: {
                type: Array,
            },
        },
        data () {
            return {
                formSearchData: {
                    filterText: '',
                    location : {id:'', name:''},
                    service: {id:'', name:''},
                    service_group : {id:'', name:''},
                },
                cascade_location_service_group: [],
            }
        },
        created() {
        },
        methods: {
            doFilter () {
                this.$events.fire('cs-order-activity-list-filter-set', this.formSearchData)
            },
            resetFilter () {
                this.formSearchData = {
                    filterText: '',
                    location : {id:'', name:''},
                    service: {id:'', name:''},
                    service_group : {id:'', name:''},
                };
                this.cascade_location_service_group = [];
                this.$events.fire('cs-order-activity-list-filter-reset')
            },
            openSimulator() {
                console.log('OrderActivityFilterBar -> openSimulator()');
                this.$emit('onOpenCPMSimulatorModal', this.formSearchData.service.id);
            },
            handleChange (value, selectedData) {
                console.log('handleChange value=', value);
                console.log('handleChange selectedData=', selectedData);
                this.formSearchData.service = {id:'', name:''};
                this.formSearchData.service_group = {id:'', name:''};
                if (selectedData.length > 0)
                {
                    this.formSearchData.service = {id: selectedData[0].value, name: selectedData[0].label};
                    if (selectedData.length > 1)
                        this.formSearchData.service_group = {id: selectedData[1].value, name: selectedData[1].label};
                }
            },
        }
    }
</script>
<style scoped>
    .cascade-service-group {
        display: inline-block;
    }
</style>