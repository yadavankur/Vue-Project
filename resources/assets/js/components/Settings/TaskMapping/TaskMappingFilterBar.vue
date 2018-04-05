<template>
    <div class="filter-bar">
        <form class="form-inline form-group-sm ">
            <div class="form-group">
                <label>Search for:</label>
                <Select clearable filterable v-model="filterData.location.id"
                        @on-change="onChangeLocation"
                        placeholder="Please select a location..."
                        style="width:180px"
                >
                    <Option v-for="item in locationOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                </Select>
                <input type="text" v-model="filterData.filterText" class="form-control" placeholder="task name...">
                <button class="btn btn-success btn-sm" @click.prevent="doFilter">Search</button>
                <button class="btn btn-warning btn-sm" @click.prevent="resetFilter">Reset</button>
                <button class="btn btn-primary btn-sm" title="New" @click.prevent="onNewTaskMapping">
                    <span class="glyphicon glyphicon-plus"></span>NEW
                </button>
            </div>
        </form>
    </div>
</template>

<script>
    import input from 'vue-strap/src/Input'
    import * as api from '../../../config';

    export default {
        created() {
            console.log('TaskMappingFilterBar Component created.');
            this.getAssingedLocations();
        },
        components: {
            'bs-input': input,
        },
        data () {
            return {
                filterData: {
                    location: {id: '', name: ''},
                    filterText: '',
                },
                locationOptions: [],
            }
        },
        methods: {
            onChangeLocation(val) {
                console.log('onChangeLocation val=',val);
            },
            doFilter () {
                console.log('doFilter');
                this.$events.fire('task-mapping-list-filter-set', this.filterData);
            },
            resetFilter () {
                console.log('resetFilter');
                this.filterData.filterText = '';
                this.filterData.location = {id:'', name:''};
                this.$events.fire('task-mapping-list-filter-reset')
            },
            onNewTaskMapping() {
                console.log('TaskMappingFilterBar onNewTaskMapping');
                this.$emit('onNewTaskMapping');
            }
        }
    }
</script>
<style scoped>
</style>