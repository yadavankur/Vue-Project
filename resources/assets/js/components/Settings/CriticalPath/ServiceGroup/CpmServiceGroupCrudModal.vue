<template>
    <div>
        <custom-modal :value="isShowModal" @cancel="onClose" effect="fade">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title"> {{ title }} </h4>
            </div>
            <div slot="modal-body" class="modal-body">
                <div class="form-group">
                    <div class="form-group">
                        <div><label for="location">Location</label></div>
                        <div class="cascade-service-group">
                            <Cascader v-model="cascade_location_service"
                                      :data="cascadeServiceOptions"
                                      :disabled="formData.id != ''"
                                      placeholder="Please select a service..."
                                      @on-change="handleChange"
                            >
                            </Cascader>
                        </div>
                    </div>
                    <bs-input label="Service Group Name" type="text" required  :maxlength="255" :icon="true" v-model="formData.name"></bs-input>
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
    import modal from 'vue-strap/src/Modal'
    import input from 'vue-strap/src/Input'

    export default {
        computed: {
        },
        props: {
            isShowModal: {
                type: Boolean,
                default: false,
            },
            dataItem : {
                type: Object,
                default: null,
            },
            title: {
                type: String,
                default: '',
            }
        },
        data () {
            return {
                formData: {
                    type: '',
                    name: '',
                    comment: '',
                    id: '',
                    location: {name:'', id:''},
                    service: {name:'', id:''},
                },
                cascadeServiceOptions: [],
                cascade_location_service: [],
            }
        },
        created() {
            console.log('CustomModal Component created.');
            this.getCascadeLocationServices();
        },
        components: {
            'custom-modal': modal,
            'bs-input': input,
        },
        mounted() {
            console.log('CustomModal Component mounted.');
        },
        methods: {
            OnSave() {
                console.log('OnSave');
                this.formData.type = this.dataItem.data.action;
                switch (this.dataItem.data.action) {
                    case 'Add':
                        this.$store.dispatch('addCpmServiceGroupRequest', this.formData)
                            .then((response) => {
                                console.log('addCpmServiceGroupRequest fire events -> refreshCpmServiceGroupTable');
                                this.$emit('onCloseCrudModal');
                                this.$events.fire('refreshCpmServiceGroupTable');
                            })
                            .catch((error) => {});
                        break;
                    case 'Edit':
                        this.$store.dispatch('updateCpmServiceGroupRequest', this.formData)
                            .then((response) => {
                                console.log('updateCpmServiceGroupRequest fire events -> refreshCpmServiceGroupTable');
                                this.$emit('onCloseCrudModal');
                                this.$events.fire('refreshCpmServiceGroupTable');
                            })
                            .catch((error) => {});
                        break;
                }
            },
            onClose() {
                console.log('onClose');
                this.$emit('onCloseCrudModal');
                this.resetFormData();
            },
            resetFormData() {
                this.formData = {
                    type: '',
                    name: '',
                    comment: '',
                    id: '',
                    location: {name:'', id:''},
                    service: {name:'', id:''},
                };
                this.cascade_location_service = [];
            },
            handleChange (value, selectedData) {
                console.log('handleChange value=', value);
                console.log('handleChange selectedData=', selectedData);
                this.formData.location = {id:'', name:''};
                this.formData.service = {id:'', name:''};
                if (selectedData.length > 0)
                {
                    this.formData.location = {id: selectedData[0].value, name: selectedData[0].label};
                    this.formData.service = {id: selectedData[selectedData.length-1].value, name: selectedData[selectedData.length-1].label};
                }
            },
        },
        watch: {
            dataItem() {
                console.log('watch -> dataItem dataItem=', this.dataItem);
                if (this.dataItem && this.dataItem.data.data && this.dataItem.data.data.service)
                {
                    this.cascade_location_service.push(this.dataItem.data.data.service.location.id);
                    this.cascade_location_service.push(this.dataItem.data.data.service.id);
                    this.formData.location = {id: this.dataItem.data.data.service.location.id, name: this.dataItem.data.data.service.location.name};
                    this.formData.service = {id: this.dataItem.data.data.service.id, name: this.dataItem.data.data.service.name};
                    this.formData.name = this.dataItem.data.data.name;
                    this.formData.id = this.dataItem.data.data.id;
                    this.formData.comment = this.dataItem.data.data.comment;
                    this.formData.type = this.dataItem.data.action;
                }
            }
        }
    }
</script>

<style scoped>
</style>
