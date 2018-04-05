<template>
    <div class="filter-bar">
        <form class="form-inline form-group-sm">
            <div class="form-group">
                <label >Search for:</label>
                    <Select clearable filterable v-model="formSearchData.location"
                            @on-change="onChangeLocation"
                            placeholder="location..."
                            style="width:160px"
                    >
                        <Option v-for="item in locationOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                    </Select>
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
            console.log('CpmServiceCustomFilterBar Component created.');
            this.getAssingedLocations();
        },
        props: {
        },
        data () {
            return {
                formSearchData: {
                    filterText: '',
                    location: '',
                },
                locationOptions: [],
            }
        },
        methods: {
            onChangeLocation(val) {
                console.log('onChangeLocation val=',val);
            },
            doFilter () {
                this.$events.fire('cpm-service-filter-set', this.formSearchData)
            },
            resetFilter () {
                this.formSearchData = {
                    filterText: '',
                    location: '',
                };
                this.$events.fire('cpm-service-filter-reset')
            },
            onClickNew() {
                console.log('CpmServiceCustomFilterBar onClickNew');
                this.$emit('onClickNew', {action: 'Add'});
            }
        }
    }
</script>
<style scoped>
</style>