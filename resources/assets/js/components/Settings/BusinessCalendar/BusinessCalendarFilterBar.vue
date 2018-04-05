<template>
    <div class="filter-bar">
        <form class="form-inline form-group-sm">
            <div class="form-group">
                <label>Search for:</label>
                <Select clearable filterable v-model="formSearchData.location"
                        @on-change="onChangeLocation"
                        placeholder="location..."
                        style="width:160px"
                >
                    <Option v-for="item in locationOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                </Select>
                <Select clearable filterable v-model="formSearchData.type"
                        @on-change="onChangeType"
                        placeholder="type..."
                        style="width:160px"
                >
                    <Option v-for="item in typeOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                </Select>
                <Select clearable filterable v-model="formSearchData.year"
                        @on-change="onChangeYear"
                        placeholder="year..."
                        style="width:80px"
                >
                    <Option v-for="item in yearOptions" :value="item" :key="item" :label="item">{{ item }}</Option>
                </Select>
                <bs-input placeholder="Description..." type="text" :rows="1" :maxlength="255" v-model="formSearchData.filterText"></bs-input>
                <button class="btn btn-success btn-sm"
                        :disabled="formSearchData.location? false: true"
                        @click.prevent="doFilter">Search
                </button>
                <!--<button class="btn btn-warning btn-sm" @click.prevent="resetFilter">Reset</button>-->
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
        created() {
            console.log('BusinessCalendarFilterBar Component created.');
            this.getAssingedLocations();
            this.getFilterOptions();
        },
        data () {
            return {
                formSearchData: {
                    filterText: '',
                    location: '',
                    year: '',
                    type: '',
                },
                locationOptions: [],
                typeOptions: [],
                yearOptions: [],
            }
        },
        components: {
            'bs-input': input,
        },
        methods: {
            doFilter () {
                this.$events.fire('business-calendar-filter-set',  this.formSearchData)
            },
            resetFilter () {
                this.formSearchData.filterText = '';
                this.formSearchData.location = '';
                this.formSearchData.type = '';
                this.formSearchData.year = '';
                this.$events.fire('business-calendar-filter-reset')
            },
            onClickNew() {
                console.log('BusinessCalendarFilterBar onClickNew');
                this.$emit('onClickNew');
            },
            onChangeLocation(val) {
                console.log('onChangeLocation val=',val);
            },
            onChangeType(val) {
                console.log('onChangeType val=',val);
            },
            onChangeYear(val) {
                console.log('onChangeYear val=',val);
            },
            getFilterOptions()
            {
                let payload = {
                    type: 'All'
                };
                this.$store.dispatch('getBusinessCalendarFilterOptions', payload)
                    .then((response) => {
                        console.log('getBusinessCalendarFilterOptions success=', response);
                        this.typeOptions = response.data.types;
                        this.yearOptions = response.data.years;
                    })
                    .catch((error) => {
                        console.error('getBusinessCalendarFilterOptions error=', error);
                    });
            },
        }
    }
</script>
