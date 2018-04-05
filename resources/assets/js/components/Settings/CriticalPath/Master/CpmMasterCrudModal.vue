<template>
    <div>
        <custom-modal :value="showModal" @cancel="onClose" effect="fade">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title"> {{ title }} </h4>
            </div>
            <div slot="modal-body" class="modal-body">
                <div class="form-group">
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
                     <div class="form-group">
                        <div><label for="predecessor">Predecessor</label></div>
                         <Select clearable filterable v-model="formData.predecessor.id"
                                 @on-change="OnChanged"
                                 placeholder="Please select a predecessor..."
                         >
                             <Option v-for="item in activityOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                         </Select>
                    </div>
                    <div class="form-group">
                        <div><label for="successor">Successor</label></div>
                        <Select clearable filterable v-model="formData.successor.id"
                                @on-change="OnChanged"
                                placeholder="Please select a successor..."
                        >
                            <Option v-for="item in activityOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                        </Select>
                    </div>
                    <bs-input label="Comment" type="textarea" :maxlength="255" :icon="true" v-model="formData.comment"></bs-input>
                </div>
            </div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-success" @click="onClose">Cancel</button>
                <button type="button" class="btn btn-primary" @click="OnSave">Save</button>
            </div>
        </custom-modal>
    </div>
</template>

<script>
    import Vue from 'vue'
    import { mapState } from 'vuex'
    import modal from 'vue-strap/src/Modal'
    import input from 'vue-strap/src/Input'

    export default {
        computed: {
            ...mapState({
                showModal: state => state.cpmMaster.showModal,
                cpmMasterData: state=> state.cpmMaster.cpmMasterData,
            }),
        },
        props: {
            cascadeServiceOptions: {
                type: Array,
                default: [],
            }
        },
        data () {
            return {
                title: '',
                formData: {
                    service: {id: '', name: ''},
                    activity: {id: '', name: ''},
                    predecessor: {id: '', name: ''},
                    comment: '',
                    id: '',
                    successor: {id: '', name: ''},
                },
                activityOptions: [],
                cascade_location_service:[],
                isDisabled: false,
            }
        },
        created() {
            console.log('CustomModal Component created.');

        },
        components: {
            'custom-modal': modal,
            'bs-input': input,
        },
        mounted() {
            console.log('CustomModal Component mounted. cpmMasterData=', this.cpmMasterData)
        },
        methods: {
            handleChange (value, selectedData) {
                console.log('handleChange value=', value);
                console.log('handleChange selectedData=', selectedData);
                this.formData.service = {id:'', name:''};
                if (selectedData.length > 0)
                {
                    // service id
                    this.formData.service.id = selectedData[selectedData.length-1].value;
                    this.formData.service.name = selectedData[selectedData.length-1].label;
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
            OnChanged(selVal) {
                console.log('OnChanged selVal=', selVal);
            },
            setActivityOptions(activities) {
                console.log('setActivityOptions activities=',activities);
                let options = [];
                for (let activity in activities) {
                    options.push({value: activities[activity].id, label: activities[activity].name});
                }
                this.activityOptions = options;
            },
            OnSave() {
                console.log('OnSave');
                let payload = {
                    isShow: false,
                    data: this.formData,
                };
                if (this.cpmMasterData.action === 'Add')
                {
                    this.$store.dispatch('setCpmMasterShowModal', payload);
                    this.$store.dispatch('addCpmMasterRequest', this.formData)
                        .then((response) => {
                            console.log('addCpmMasterRequest fire events -> refreshCpmMasterTable');
                            this.$events.fire('refreshCpmMasterTable');
                        })
                        .catch((error) => {});
                }
                else if (this.cpmMasterData.action === 'Edit')
                {
                    // update
                    this.$store.dispatch('setCpmMasterShowModal', payload);
                    this.$store.dispatch('updateCpmMasterRequest', this.formData)
                        .then((response) => {
                            console.log('updateCpmMasterRequest fire events -> refreshCpmMasterTable');
                            this.$events.fire('refreshCpmMasterTable');
                        })
                        .catch((error) => {});
                }
                else
                {
                    // error
                }
            },
            onClose() {
                console.log('onClose');
                let payload = {
                    isShow: false,
                    data: this.formData,
                };
                this.$store.dispatch('setCpmMasterShowModal', payload);
                this.resetFormData();
            },
            resetFormData() {
                this.formData = {
                    service: {id: '', name: ''},
                    activity: {id: '', name: ''},
                    predecessor: {id: '', name: ''},
                    comment: '',
                    id: '',
                    successor: {id: '', name: ''},
                };
                this.cascade_location_service = [];
                this.isDisabled = false;
                this.activityOptions = [];

            }
        },
        watch: {
            cpmMasterData() {
                console.log('+++++++++++++ cpmMasterData changed =', this.cpmMasterData);
                if (this.cpmMasterData && this.cpmMasterData.action === 'Add')
                {
                    this.resetFormData();
                    this.title = 'Adding a new master activity';
                    this.isDisabled = false;
                }
                else if (this.cpmMasterData && this.cpmMasterData.action === 'Edit')
                {
                    this.resetFormData();
                    this.title = 'Editing the master activity';
                    // get state list
                    this.$store.dispatch('getCpmActivityOptions', this.cpmMasterData.data.service.id)
                        .then((response) => {
                            console.log('created getCpmActivityOptions success=', response);
                            this.setActivityOptions(response.data.activities);
                            this.isDisabled = true;
                            this.formData.id = this.cpmMasterData.data.id;
                            this.formData.activity.id = this.cpmMasterData.data.activity.id;
                            this.formData.activity.name = this.cpmMasterData.data.activity.name;
                            this.formData.service.id = this.cpmMasterData.data.service.id;
                            this.formData.service.name = this.cpmMasterData.data.service.name;
                            this.formData.predecessor.name = this.cpmMasterData.data.predecessor? this.cpmMasterData.data.predecessor.name: '';
                            this.formData.predecessor.id = this.cpmMasterData.data.predecessor? this.cpmMasterData.data.predecessor.id: '';
                            this.formData.comment = this.cpmMasterData.data.comment;
                            this.formData.successor.name = this.cpmMasterData.data.successor? this.cpmMasterData.data.successor.name: '';
                            this.formData.successor.id = this.cpmMasterData.data.successor? this.cpmMasterData.data.successor.id: '';
                            this.cascade_location_service.push(this.cpmMasterData.data.service.location.id);
                            this.cascade_location_service.push(this.formData.service.id);
                        })
                        .catch((error) => {
                            console.error('getCpmActivityOptions error=', error);
                        });
                }
            }
        }
    }
</script>

<style scoped>
    /*.modal-header {*/
        /*padding: 15px;*/
        /*border-bottom: 1px solid #e5e5e5;*/
        /*color: white !important;*/
        /*background-color: #0a5b9e !important;*/
        /*border-color: #0a5b9e;*/
        /*border-top-right-radius: 3px;*/
        /*border-top-left-radius: 3px;*/
    /*}*/
</style>
