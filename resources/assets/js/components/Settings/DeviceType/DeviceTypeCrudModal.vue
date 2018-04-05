<template>
    <div>
        <custom-modal :value="showModal" @cancel="onClose" effect="fade">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title"> {{ title }} </h4>
            </div>
            <div slot="modal-body" class="modal-body">
                <div class="form-group">
                    <bs-input label="Device Type Name" type="text" required  :maxlength="255" :icon="true" v-model="formData.name"></bs-input>
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
                showModal: state => state.deviceType.showModal,
                deviceTypeData: state=> state.deviceType.deviceTypeData,
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
                },
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
            console.log('CustomModal Component mounted. deviceTypeData=', this.deviceTypeData)
        },
        methods: {
            OnSave() {
                console.log('OnSave');
                let payload = {
                    isShow: false,
                    data: this.formData,
                };
                if (this.deviceTypeData.action === 'Add')
                {
                    // new tab
                    this.$store.dispatch('setDeviceTypeShowModal', payload);
                    this.$store.dispatch('addDeviceTypeRequest', this.formData)
                        .then((response) => {
                            console.log('addDeviceTypeRequest fire events -> refreshDeviceTypeTable');
                            this.$events.fire('refreshDeviceTypeTable');
                        })
                        .catch((error) => {});
                }
                else if (this.deviceTypeData.action === 'Edit')
                {
                    // update
                    this.$store.dispatch('setDeviceTypeShowModal', payload);
                    this.$store.dispatch('updateDeviceTypeRequest', this.formData)
                        .then((response) => {
                            console.log('updateDeviceTypeRequest fire events -> refreshDeviceTypeTable');
                            this.$events.fire('refreshDeviceTypeTable');
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
                this.$store.dispatch('setDeviceTypeShowModal', payload);
                this.resetFormData();
            },
            resetFormData() {
                this.formData = {
                    name: '',
                    comment: '',
                    id: '',
                };
            }
        },
        watch: {
            deviceTypeData() {
                console.log('+++++++++++++ deviceTypeData changed =', this.deviceTypeData);
                if (this.deviceTypeData && this.deviceTypeData.action === 'Add')
                {
                    this.resetFormData();
                    this.title = 'Adding a new device type';
                }
                else if (this.deviceTypeData && this.deviceTypeData.action === 'Edit')
                {
                    this.resetFormData();
                    this.title = 'Editing the device type';
                    this.formData.id = this.deviceTypeData.data.id;
                    this.formData.name = this.deviceTypeData.data.name;
                    this.formData.comment = this.deviceTypeData.data.comment;
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
