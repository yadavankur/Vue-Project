<template>
    <div class="filter-bar">
        <form class="form-inline form-group-sm ">
            <div class="form-group">
                <label>Search for:</label>
                <Select clearable filterable v-model="formSearchData.type"
                        placeholder="Please select a type..."
                        style="width:180px"
                >
                    <Option v-for="item in typeOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                </Select>
                <Select clearable filterable v-model="formSearchData.level"
                        placeholder="Please select a level..."
                        style="width:180px"
                >
                    <Option v-for="item in levelOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                </Select>
                <input type="text" v-model="formSearchData.filterText" class="form-control" @keyup.enter="doFilter" placeholder="type...">
                <button class="btn btn-success btn-sm" @click.prevent="doFilter">Search</button>
                <button class="btn btn-warning btn-sm" @click.prevent="resetFilter">Reset</button>
            </div>
        </form>
    </div>
</template>

<script>
    import input from 'vue-strap/src/Input'

    export default {
        created() {
            console.log('LogFilterBar Component created.');
        },
        components: {
            'bs-input': input,
        },
        data () {
            return {
                formSearchData: {
                    type: 'USER_ACTION',
                    level: 'ERROR',
                    filterText: '',
                },
                typeOptions: [
                    {value: 'USER_ACTION', label: 'USER_ACTION'},
                    {value: 'SYSTEM_ACTION', label: 'SYSTEM_ACTION'},
                ],
                levelOptions: [
                    {value: 'ERROR', label: 'ERROR'},
                    {value: 'NORMAL', label: 'NORMAL'},
                ],
            }
        },
        methods: {
            doFilter () {
                console.log('doFilter');
                this.$events.fire('log-list-filter-set', this.formSearchData);
            },
            resetFilter () {
                console.log('resetFilter');
                this.formSearchData = {
                    type: 'USER_ACTION',
                    level: 'ERROR',
                    filterText: '',
                };
                this.$events.fire('log-list-filter-reset')
            },
        }
    }
</script>
<style scoped>
</style>