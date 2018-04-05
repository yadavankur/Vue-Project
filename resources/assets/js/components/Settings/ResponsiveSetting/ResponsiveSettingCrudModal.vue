<template>
    <div>
        <custom-modal :value="showModal" @cancel="onClose" effect="fade">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title"> {{ title }} </h4>
            </div>
            <div slot="modal-body" class="modal-body">
                <div class="form-group">
                    <div class="form-group">
                        <div><label for="deviceType">Device Type</label></div>
                        <Select clearable filterable labelInValue v-model="formData.device_type.id"
                                @on-change="OnChanged"
                                size="large"
                                placeholder="Please select a device type..."
                        >
                            <Option v-for="item in deviceTypeOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                        </Select>
                    </div>
                    <div class="form-group">
                        <div><label for="component">Component</label></div>
                        <Cascader v-model="cascade_page_component"
                                  :data="cascadeComponentOptions"
                                  size="large"
                                  change-on-select
                                  placeholder="Please select a page/parent component..."
                                  @on-change="handleChange"
                        >
                        </Cascader>
                    </div>
                    <bs-input label="Column Name" type="text" required  :maxlength="255" :icon="true" v-model="formData.column_name"></bs-input>
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
                showModal: state => state.responsiveSetting.showModal,
                responsiveSettingData: state=> state.responsiveSetting.responsiveSettingData,
            }),
        },
        props: {
            deviceTypeOptions: {
                type: Array,
                default: [],
            },
            cascadeComponentOptions: {
                type: Array,
                default: [],
            }
        },
        data () {
            return {
                title: '',
                formData: {
                    column_name: '',
                    comment: '',
                    id: '',
                    device_type_id: '',
                    device_type: {id:'', name:''},
                    component_id: '',
                    component: {id:'', name:''},
                },
                //deviceTypeOption: null,
                cascade_page_component: [],
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
            console.log('CustomModal Component mounted. responsiveSettingData=', this.responsiveSettingData)
        },
        methods: {
            OnChanged(selVal) {
                console.log('OnChanged selVal=', selVal);
                if (!selVal) return;
                this.formData.device_type.name = selVal.label;
            },
            handleChange (value, selectedData) {
                console.log('handleChange value=', value);
                console.log('handleChange selectedData=', selectedData);
                this.formData.component = {id:'', name:''};
                if (selectedData.length > 0)
                {
                    this.formData.component = {id: selectedData[selectedData.length-1].value, name: selectedData[selectedData.length-1].label};
                }
            },
            OnSave() {
                let payload = {
                    isShow: false,
                    data: this.formData,
                };
                if (this.responsiveSettingData.action === 'Add')
                {
                    // new tab
                    this.$store.dispatch('setResponsiveSettingShowModal', payload);
                    this.$store.dispatch('addResponsiveSettingRequest', this.formData)
                        .then((response) => {
                            console.log('addResponsiveSettingRequest fire events -> refreshResponsiveSettingTable');
                            this.$events.fire('refreshResponsiveSettingTable');
                        })
                        .catch((error) => {});
                }
                else if (this.responsiveSettingData.action === 'Edit')
                {
                    // update
                    this.$store.dispatch('setResponsiveSettingShowModal', payload);
                    this.$store.dispatch('updateResponsiveSettingRequest', this.formData)
                        .then((response) => {
                            console.log('updateResponsiveSettingRequest fire events -> refreshResponsiveSettingTable');
                            this.$events.fire('refreshResponsiveSettingTable');
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
                this.$store.dispatch('setResponsiveSettingShowModal', payload);
                this.resetFormData();
            },
            resetFormData() {
                this.formData = {
                    column_name: '',
                    comment: '',
                    id: '',
                    device_type_id: '',
                    device_type: {id:'', name:''},
                    component_id: '',
                    component: {id:'', name:''},
                };
                this.cascade_page_component = [];
            }
        },
        watch: {
            responsiveSettingData() {
                console.log('+++++++++++++ responsiveSettingData changed =', this.responsiveSettingData);
                if (this.responsiveSettingData && this.responsiveSettingData.action === 'Add')
                {
                    this.resetFormData();
                    this.title = 'Adding a new responsive setting';
                }
                else if (this.responsiveSettingData && this.responsiveSettingData.action === 'Edit')
                {
                    this.resetFormData();
                    this.title = 'Editing the responsive setting';
                    this.formData.id = this.responsiveSettingData.data.id;
                    this.formData.column_name = this.responsiveSettingData.data.column_name;
                    this.formData.comment = this.responsiveSettingData.data.comment;
                    this.formData.component = {
                                                name: this.responsiveSettingData.data.component.name,
                                                id: this.responsiveSettingData.data.component.id,
                                              };
                    this.formData.device_type = {
                                                name: this.responsiveSettingData.data.device_type.name,
                                                id: this.responsiveSettingData.data.device_type.id,
                                               };
                    this.cascade_page_component.push(this.responsiveSettingData.data.component.page.id);
                    if (this.responsiveSettingData.data.component.parent)
                    {
                        if (this.responsiveSettingData.data.component.parent.parent)
                        {
                            this.cascade_page_component.push(this.responsiveSettingData.data.component.parent.parent.id);
                        }
                        this.cascade_page_component.push(this.responsiveSettingData.data.component.parent.id);
                    }
                    this.cascade_page_component.push(this.responsiveSettingData.data.component.id);
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
