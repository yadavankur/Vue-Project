<template>
    <div>
        <div>
            <div class="business-calendar-content">
                <div class="business-calendar-row">
                    <div class="block_title"><label for="Location">Location</label></div>
                    <div class="block_content">
                        <Select :value="location"
                                @on-change="onChangeLocation"
                                placeholder="location..."
                                :disabled="formData.id != ''"
                                :clearable="formData.id == ''"
                                :filterable="formData.id == ''"
                        >
                            <Option v-for="item in locationOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                        </Select>
                    </div>
                </div>
                <div class="business-calendar-row">
                    <div class="block_title"><label for="Type">Type</label></div>
                    <div class="block_content">
                        <Select clearable filterable :value="type"
                                @on-change="onChangeType"
                                placeholder="type..."
                        >
                            <Option v-for="item in typeOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                        </Select>
                    </div>
                </div>
                <div class="business-calendar-row">
                    <div class="block_title"><label for="Date">Date</label></div>
                    <div class="block_content">
                        <Date-picker
                                id="businessDate"
                                :value="businessDate"
                                format="dd/MM/yyyy" type="date"
                                placeholder="Date..."
                                @on-change="onChangeDate"
                        >
                        </Date-picker>
                    </div>
                </div>
                <div class="business-calendar-row">
                    <div class="block_title"><label for="Description">Description</label></div>
                    <div class="block_content">
                        <Input v-model="formData.description" placeholder="Please input the description ..."></Input>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'

    export default {
        props: {
            dataItem: {
                type: Object,
                default: null,
            },
        },
        data () {
            return {
                formData: {
                    id: '',
                    location: '',
                    year: '',
                    type: '',
                    businessDate: '',
                    description: '',
                },
                businessDate: '',
                typeOptions: [],
                yearOptions: [],
                locationOptions: [],
                location: '',
                type: '',
            }
        },
        computed: {
            ...mapState({
                user: state => state.authUser,
            }),
        },
        created() {
            console.log('BusinessCalendarBoday Component created.')
            this.getAssingedLocations();
            this.getFilterOptions();
            // get data from dataItem
            if (this.dataItem && this.dataItem.data)
            {
                this.type = this.dataItem.data.type;
                this.businessDate = this.dataItem.data.value;
                this.location = this.dataItem.data.location.id;
                this.businessDate = this.getFormatDate(this.businessDate);

                this.formData.id = this.dataItem.data.id;
                this.formData.type = this.dataItem.data.type;
                this.formData.location = this.dataItem.data.location.id;
                this.formData.businessDate = this.dataItem.data.value;
                this.formData.description = this.dataItem.data.description;
            }
        },
        components: {
        },
        mounted() {
            console.log('BusinessCalendarBoday Component mounted.')
        },
        methods:
        {
            onChangeDate(val) {
                console.log('BusinessCalendarBoday onChangeDate val=', val);
                this.formData.businessDate = val;
            },
            onChangeLocation(val) {
                console.log('BusinessCalendarBoday onChangeLocation. val=', val);
                this.formData.location = val;
            },
            onChangeType(val) {
                console.log('BusinessCalendarBoday onChangeType val=', val);
                this.formData.type = val;
            },
            getFilterOptions()
            {
                let payload = {
                    type: 'Default'
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

<style>
    label {
        margin-top: 5px;
    }
    .block_title {
    }
    .block_content {
    }
    .business-calendar-row {
        margin-top: 10px;
        margin-bottom: 10px;
    }
</style>
