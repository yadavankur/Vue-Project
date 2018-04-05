<template>
    <div>
        <div class="form-group-sm">
            <bs-input label="Task Name" type="text" :maxlength="255" v-model="formData.task_name"></bs-input>
            <div class="form-group">
                <div><label for="activity">Location/Service</label></div>
                <Cascader v-model="cascade_location_service"
                          :data="cascadeServiceOptions"
                          placeholder="Please select a location/service..."
                          @on-change="handleChange"
                          :disabled="isDisabled"
                          :clearable="false"
                >
                </Cascader>
            </div>
            <div class="form-group">
                <div><label for="activity">Activity</label></div>
                <Select clearable filterable v-model="formData.activity.id"
                        @on-change="OnChanged"
                        placeholder="Please select an activity..."
                >
                    <Option v-for="item in activityOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                </Select>
            </div>
            <bs-input label="Comment" type="textarea" :maxlength="255" :icon="true" v-model="formData.comment"></bs-input>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import input from 'vue-strap/src/Input'

    export default {
        props: {
            itemData: {
                type: Object,
                default: null,
            }
        },
        computed: {
            ...mapState({
                user: state => state.authUser,
            }),
        },
        data () {
            return {
                cascadeServiceOptions: [],
                formData: {
                    id: '',
                    task_name: '',
                    comment: '',
                    location: {id: '', name: ''},
                    service: {id: '', name: ''},
                    activity: {id: '', name: ''},
                },
                activityOptions: [],
                cascade_location_service:[],
                isDisabled: false,
            }
        },
        created() {
            console.log('TaskMappingBody Component created.');
            if (this.isEmpty(this.itemData))
            {
                this.formData = {
                    id: '',
                    task_name: '',
                    comment: '',
                    location: {id: '', name: ''},
                    service: {id: '', name: ''},
                    activity: {id: '', name: ''},
                };
                this.cascade_location_service = [];
            }
            else
            {
                this.formData = {
                    id: this.itemData.id,
                    task_name: this.itemData.task_name,
                    comment: this.itemData.comment,
                    location: {id: this.itemData.location.id, name: this.itemData.location.name},
                    service: {id: this.itemData.activity.service.id, name: this.itemData.activity.service.name},
                    activity: {id: this.itemData.activity.id, name: this.itemData.activity.name},
                };
                this.cascade_location_service = [this.itemData.location.id, this.itemData.activity.service.id];
                this.$store.dispatch('getCpmActivityOptions', this.formData.service.id)
                    .then((response) => {
                        console.log('created getCpmActivityOptions success=', response);
                        this.setActivityOptions(response.data.activities);
                    })
                    .catch((error) => {
                        console.error('getCpmActivityOptions error=', error);
                    });
            }
            this.$store.dispatch('getCpmCascadeServiceOptions')
                .then((response) => {
                    console.log('created getCpmCascadeServiceOptions success=', response);
                    this.cascadeServiceOptions = response.data.locationServices;
                })
                .catch((error) => {
                    console.error('getCpmCascadeServiceOptions error=', error);
                });

        },
        components: {
            'bs-input': input,
        },
        mounted() {
            console.log('TaskMappingBody Component mounted.')
        },
        methods: {
            OnChanged() {

            },
            handleChange (value, selectedData) {
                console.log('handleChange value=', value);
                console.log('handleChange selectedData=', selectedData);
                this.formData.location = {id:'', name:''};
                this.formData.service = {id:'', name:''};
                if (selectedData.length > 0)
                {
                    // location id
                    this.formData.location.id = selectedData[0].value;
                    this.formData.location.name = selectedData[0].label;

                    // service id
                    this.formData.service.id = selectedData[selectedData.length-1].value;
                    this.formData.service.name = selectedData[selectedData.length-1].label;
                }
                else
                {
                    return;
                }
                // get all activities belong to this service id
                // get state list
                this.$store.dispatch('getCpmActivityOptions', this.formData.service.id)
                    .then((response) => {
                        console.log('created getCpmActivityOptions success=', response);
                        this.setActivityOptions(response.data.activities);
                    })
                    .catch((error) => {
                        console.error('getCpmActivityOptions error=', error);
                    });
            },
            setActivityOptions(activities) {
                console.log('setActivityOptions activities=',activities);
                let options = [];
                for (let activity in activities) {
                    options.push({value: activities[activity].id, label: activities[activity].name});
                }
                this.activityOptions = options;
            },
        }
    }
</script>

<style scoped>
    label {
        margin-top: 5px;
    }
</style>
