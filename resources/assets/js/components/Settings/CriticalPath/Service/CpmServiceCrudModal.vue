<template>
    <div>
        <custom-modal :value="showModal" @cancel="onClose" effect="fade">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title"> {{ title }} </h4>
            </div>
            <div slot="modal-body" class="modal-body">
                <div class="form-group">
                    <div class="form-group">
                        <div><label for="location">Location</label></div>
                        <Cascader v-model="cascade_state_location"
                                  :data="cascadeLocationOptions"
                                  size="large"
                                  placeholder="Please select a state/location..."
                                  @on-change="handleChange"
                                  :disabled="formData.id != ''"
                        >
                        </Cascader>
                    </div>
                    <bs-input label="Service Name" type="text" required  :maxlength="255" :icon="true" v-model="formData.name"></bs-input>
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
                showModal: state => state.cpmService.showModal,
                cpmServiceData: state=> state.cpmService.cpmServiceData,
            }),
        },
        props: {
        },
        data () {
            return {
                title: '',
                formData: {
                    name: '',
                    comment: '',
                    id: '',
                    location_id: '',
                    location: {name:'', id:''},
                    state: {name:'', id:''},
                },
                cascadeLocationOptions: [],
                cascade_state_location: [],
            }
        },
        created() {
            console.log('CustomModal Component created.');
            // get location list
            this.$store.dispatch('getAssignedCascadeLocationOptions')
                .then((response) => {
                    console.log('created getAssignedCascadeLocationOptions success=', response);
                    this.cascadeLocationOptions = response.data.cascadeLocations;
                })
                .catch((error) => {
                        console.error('getAssignedCascadeLocationOptions error=', error);
                });
        },
        components: {
            'custom-modal': modal,
            'bs-input': input,
        },
        mounted() {
            console.log('CustomModal Component mounted. cpmServiceData=', this.cpmServiceData)
        },
        methods: {
            handleChange (value, selectedData) {
                console.log('handleChange value=', value);
                console.log('handleChange selectedData=', selectedData);
                this.formData.location = {id:'', name:''};
                this.formData.state = {id:'', name:''};
                if (selectedData.length > 0)
                {
                    this.formData.state = {id: selectedData[0].value, name: selectedData[0].label};
                    this.formData.location = {id: selectedData[selectedData.length-1].value, name: selectedData[selectedData.length-1].label};
                }
            },
            OnSave() {
                console.log('OnSave');
                let payload = {
                    isShow: false,
                    data: this.formData,
                };
                if (this.cpmServiceData.action === 'Add')
                {
                    // new tab
                    this.$store.dispatch('setCpmServiceShowModal', payload);
                    this.$store.dispatch('addCpmServiceRequest', this.formData)
                        .then((response) => {
                            console.log('addCpmServiceRequest fire events -> refreshCpmServiceTable');
                            this.$events.fire('refreshCpmServiceTable');
                        })
                        .catch((error) => {});
                }
                else if (this.cpmServiceData.action === 'Edit')
                {
                    // update
                    this.$store.dispatch('setCpmServiceShowModal', payload);
                    this.$store.dispatch('updateCpmServiceRequest', this.formData)
                        .then((response) => {
                            console.log('updateCpmServiceRequest fire events -> refreshCpmServiceTable');
                            this.$events.fire('refreshCpmServiceTable');
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
                this.$store.dispatch('setCpmServiceShowModal', payload);
                this.resetFormData();
            },
            resetFormData() {
                this.formData = {
                    name: '',
                    comment: '',
                    id: '',
                    location_id: '',
                    location: {name:'', id:''},
                    state: {name:'', id:''},
                };
                this.cascade_state_location = [];
            }
        },
        watch: {
            cpmServiceData() {
                console.log('+++++++++++++ cpmServiceData changed =', this.cpmServiceData);
                if (this.cpmServiceData && this.cpmServiceData.action === 'Add')
                {
                    this.resetFormData();
                    this.title = 'Adding a new service';
                }
                else if (this.cpmServiceData && this.cpmServiceData.action === 'Edit')
                {
                    this.resetFormData();
                    this.title = 'Editing the service';
                    this.formData.id = this.cpmServiceData.data.id;
                    this.formData.name = this.cpmServiceData.data.name;
                    this.formData.comment = this.cpmServiceData.data.comment;
                    this.formData.location_id = this.cpmServiceData.data.location.id;
                    this.formData.location.id = this.cpmServiceData.data.location.id;
                    this.formData.location.name = this.cpmServiceData.data.location.name;
                    this.formData.state.id = this.cpmServiceData.data.location.state.id;
                    this.formData.state.name = this.cpmServiceData.data.location.state.name;
                    this.cascade_state_location.push(this.formData.state.id);
                    this.cascade_state_location.push(this.formData.location.id);
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
