<template>
    <div>
        <custom-modal :value="showModal" @cancel="onClose" effect="fade">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title"> {{ title }} </h4>
            </div>
            <div slot="modal-body" class="modal-body">
                <div class="form-group">
                    <div class="form-group">
                        <div><label for="Component">Page/ParentComponent/Component</label></div>
                        <Cascader v-model="formData.cascade_component"
                                  :data="cascadeComponentOptions"
                                  size="large"
                                  change-on-select
                                  placeholder="Please select a page/parent/component..."
                                  @on-change="handleChange"
                        >
                        </Cascader>
                    </div>
                    <bs-input label="Process Name" type="text" required  :maxlength="255" :icon="true" v-model="formData.name"></bs-input>
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
    import select from 'vue-strap/src/Select'

    export default {
        computed: {
            ...mapState({
                showModal: state => state.process.showModal,
                processData: state=> state.process.processData,
            }),
        },
        props: {
            selectedComponent:'',
        },
        data () {
            return {
                title: '',
                formData: {
                    name: '',
                    comment: '',
                    id: '',
                    component_id: '',
                    component: {name:'', id:''},
                    page_id: '',
                    parent_id: '',
                    parent: { name:'', id:'' },
                    page: { name:'', id:'' },
                    cascade_component: [],
                },
                defaultComponent:'',
                componentOptions: [],
                minSearch: 1,
                cascadeComponentOptions: [],
            }
        },
        created() {
            console.log('CustomModal Process created.');
            this.$store.dispatch('getProcessCascadeComponentOptions')
                .then((response) => {
                    console.log('created getProcessCascadeComponentOptions success=', response);
                    // this.setCascaComponentOptions(response.data.cascadeComponents);
                    this.cascadeComponentOptions = response.data.cascadeComponents;
                })
                .catch((error) => {
                    console.error('getProcessCascadeComponentOptions error=', error);
                });
        },
        components: {
            'custom-modal': modal,
            'bs-input': input,
            'v-select': select,
        },
        mounted() {
            console.log('CustomModal Process mounted. processData=', this.processData)
        },
        methods: {
            handleChange (value, selectedData) {
                // this.formData.component.name = selectedData.map(o => o.label).join('/');
                console.log('handleChange value=', value);
                console.log('handleChange selectedData=', selectedData);
                this.formData.component = {id:'', name:''};
                this.formData.page = {id:'', name:''};
                this.formData.parent = {id:'', name:''};
                if (selectedData.length > 0)
                {
                    this.formData.page = {id: selectedData[0].value, name: selectedData[0].label};
                    this.formData.component = {id: selectedData[selectedData.length-1].value, name: selectedData[selectedData.length-1].label};
                }

            },
            OnSave() {
                console.log('OnSave');
                let payload = {
                    isShow: false,
                    data: this.formData,
                };
                if (this.processData.action === 'Add')
                {
                    // new tab
                    this.$store.dispatch('setProcessShowModal', payload);
                    this.$store.dispatch('addProcessRequest', this.formData)
                        .then((response) => {
                            console.log('addProcessRequest fire events -> refreshProcessTable');
                            this.$events.fire('refreshProcessTable');
                        })
                        .catch((error) => {});
                }
                else if (this.processData.action === 'Edit')
                {
                    // update
                    this.$store.dispatch('setProcessShowModal', payload);
                    this.$store.dispatch('updateProcessRequest', this.formData)
                        .then((response) => {
                            console.log('updateProcessRequest fire events -> refreshProcessTable');
                            this.$events.fire('refreshProcessTable');
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
                this.$store.dispatch('setProcessShowModal', payload);
                this.resetFormData();
            },
            resetFormData() {
                this.formData = {
                    name: '',
                    comment: '',
                    id: '',
                    component_id: '',
                    component: { name:'', id:'' },
                    page_id: '',
                    parent_id: '',
                    parent: { name:'', id:'' },
                    page: { name:'', id:'' },
                    cascade_component: [],
                };
            }
        },
        watch: {
            processData() {
                console.log('+++++++++++++ processData changed =', this.processData);
                if (this.processData && this.processData.action === 'Add')
                {
                    this.resetFormData();
                    this.title = 'Adding a new process';
                }
                else if (this.processData && this.processData.action === 'Edit')
                {
                    console.log('+++++++++++++ processData data.parent =', this.processData.data.parent);
                    this.resetFormData();
                    this.title = 'Editing the process';

                    this.formData.id = this.processData.data.id;
                    this.formData.name = this.processData.data.name;
                    this.formData.comment = this.processData.data.comment;

                    this.formData.component.id = this.processData.data.component.id;
//                    if (this.processData.data.component.page) this.formData.cascade_component.push(this.processData.data.component.page.id);
//                    if (this.processData.data.component.parent) this.formData.cascade_component.push(this.processData.data.component.parent.id);
//                    if (this.processData.data.component) this.formData.cascade_component.push(this.processData.data.component.id);
                    this.formData.component.name = this.processData.data.component.name;

                    this.formData.page = {
                        name: this.processData.data.component.page.name,
                        id: this.processData.data.component.page.id,
                    };
                    this.formData.cascade_component.push(this.processData.data.component.page.id);
                    if (this.processData.data.component.parent)
                    {
                        if (this.processData.data.component.parent.parent)
                        {
                            this.formData.cascade_component.push(this.processData.data.component.parent.parent.id);
                        }
                        this.formData.parent = {
                            name: this.processData.data.component.parent.name,
                            id: this.processData.data.component.parent.id,
                        };
                        this.formData.parent_id = this.processData.data.component.parent.id;
                        this.formData.cascade_component.push(this.processData.data.component.parent.id);
                    }
                    this.formData.page_id = this.processData.data.component.page.id;
                    this.formData.cascade_component.push(this.processData.data.component.id);
                    console.log('+++++++++++++ this.formData.component.name =', this.formData.component.name);
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
