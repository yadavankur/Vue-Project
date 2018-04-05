<template>
    <div>
        <custom-modal :value="showModal" @cancel="onClose" effect="fade">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title"> {{ title }} </h4>
            </div>
            <div slot="modal-body" class="modal-body">
                <div class="form-group">
                    <div class="form-group">
                        <div><label for="Page">Page/Parent Component</label></div>
                        <Cascader v-model="cascade_page_component"
                                  :data="options"
                                  size="large"
                                  change-on-select
                                  placeholder="Please select a page/parent component..."
                                  @on-change="handleChange"
                        >
                        </Cascader>
                    </div>
                    <bs-input label="Component Name" type="text" required  :maxlength="255" :icon="true" v-model="formData.name"></bs-input>
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
                showModal: state => state.component.showModal,
                componentData: state=> state.component.componentData,
            }),
        },
        props: {
            selectedPage: '',
            selectedParent: '',
            options: {
                type: Array,
                default: [],
            },
        },
        beforeDestroy() {
            console.log('++++++++++++++++++++ CustomModal Component beforeDestroy.');
        },
        data () {
            return {
                title: '',
                formData: {
                    name: '',
                    comment: '',
                    id: '',
                    page_id: '',
                    page: '',
                    parent_id: '',
                    parent: ''
                },
                cascade_page_component: [],
            }
        },
        beforeUpdate() {
            console.log('CustomModal Component beforeUpdate.');
        },
        created() {
            console.log('CustomModal Component created.');
        },
        components: {
            'custom-modal': modal,
            'bs-input': input,
        },
        mounted() {
            console.log('CustomModal Component mounted. componentData=', this.componentData)
        },
        methods: {
            handleChange (value, selectedData) {
                console.log('handleChange value=', value);
                console.log('handleChange selectedData=', selectedData);
                this.formData.page = {id:'', name:''};
                this.formData.parent = {id:'', name:''};
                if (selectedData.length > 0)
                {
                    this.formData.page = {value: selectedData[0].value, label: selectedData[0].label};
                    if (selectedData.length > 1)
                        this.formData.parent = {value: selectedData[selectedData.length-1].value, label: selectedData[selectedData.length-1].label};
                }
            },
            OnSave() {
                console.log('OnSave');
                let payload = {
                    isShow: false,
                    data: this.formData,
                };
                if (this.componentData.action === 'Add')
                {
                    // new tab
                    this.$store.dispatch('setComponentShowModal', payload);
                    this.$store.dispatch('addComponentRequest', this.formData)
                        .then((response) => {
                            console.log('addComponentRequest fire events -> refreshComponentTable');
                            this.$events.fire('refreshComponentTable');
                        })
                        .catch((error) => {});
                }
                else if (this.componentData.action === 'Edit')
                {
                    // update
                    this.$store.dispatch('setComponentShowModal', payload);
                    this.$store.dispatch('updateComponentRequest', this.formData)
                        .then((response) => {
                            console.log('updateComponentRequest fire events -> refreshComponentTable');
                            this.$events.fire('refreshComponentTable');
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
                this.$store.dispatch('setComponentShowModal', payload);
                this.resetFormData();
            },
            resetFormData() {
                this.formData = {
                    name: '',
                    comment: '',
                    id: '',
                    page_id: '',
                    parent_id: '',
                    parent: '',
                    page: '',
                };
            }
        },
        watch: {
            componentData() {
                console.log('+++++++++++++ componentData changed =', this.componentData);
                if (this.componentData && this.componentData.action === 'Add')
                {
                    this.resetFormData();
                    this.cascade_page_component = [];
                    this.title = 'Adding a new component';
                }
                else if (this.componentData && this.componentData.action === 'Edit')
                {
                    console.log('+++++++++++++ componentData data.parent =', this.componentData.data.parent);
                    this.resetFormData();
                    this.title = 'Editing the component';
                    this.cascade_page_component = [];
                    this.formData.id = this.componentData.data.id;
                    this.formData.name = this.componentData.data.name;
                    this.formData.comment = this.componentData.data.comment;
                    this.formData.parent_id = this.componentData.data.parent_id;
                    this.formData.page = {
                        label: this.componentData.data.page.name,
                        value: this.componentData.data.page.id,
                    };
                    this.cascade_page_component.push(this.componentData.data.page.id);
                    if (this.componentData.data.parent)
                    {
                        if (this.componentData.data.parent.parent)
                        {
                            this.cascade_page_component.push(this.componentData.data.parent.parent.id);
                        }
                        this.formData.parent = {
                            label: this.componentData.data.parent.name,
                            value: this.componentData.data.parent.id,
                        };
                        this.formData.parent_id = this.componentData.data.parent.id;
                        this.cascade_page_component.push(this.componentData.data.parent.id);
                    }
                    this.formData.page_id = this.componentData.data.page.id;

                    console.log('+++++++++++++ componentData page =', this.formData.page);
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
