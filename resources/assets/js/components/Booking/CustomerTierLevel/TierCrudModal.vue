<template>
    <div>
        <custom-modal :value="showModal" @cancel="onClose" effect="fade">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title"> {{ title }} </h4>
            </div>
            <div slot="modal-body" class="modal-body">
                <div class="form-group">
                    <bs-input label="CustomerTierLevel Name" type="text" required  :maxlength="255" :icon="true" v-model="formData.name"></bs-input>
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
                showModal: state => state.customerTierLevel.showModal,
                tierData: state=> state.customerTierLevel.customerTierLevelData,
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
                    id: ''
                },
            }
        },
        created() {
            console.log('CustomModal Component created.')
        },
        components: {
            'custom-modal': modal,
            'bs-input': input,
        },
        mounted() {
            console.log('CustomModal Component mounted. tierData=', this.tierData)
        },
        methods: {
            OnSave() {
                console.log('OnSave');
                let payload = {
                    isShow: false,
                    data: this.formData,
                };
                if (this.tierData.action === 'Add')
                {
                    // new tab
                    this.$store.dispatch('setCustomerTierLevelShowModal', payload);
                    this.$store.dispatch('addCustomerTierLevelRequest', this.formData)
                        .then((response) => {
                            console.log('addCustomerTierLevelRequest fire events -> refreshCustomerTierLevelTable');
                            this.$events.fire('refreshCustomerTierLevelTable');
                        })
                        .catch((error) => {});
                }
                else if (this.tierData.action === 'Edit')
                {
                    // update
                    this.$store.dispatch('setCustomerTierLevelShowModal', payload);
                    this.$store.dispatch('updateCustomerTierLevelRequest', this.formData)
                        .then((response) => {
                            console.log('updateCustomerTierLevelRequest fire events -> refreshCustomerTierLevelTable');
                            this.$events.fire('refreshCustomerTierLevelTable');
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
                this.$store.dispatch('setCustomerTierLevelShowModal', payload);
                this.resetFormData();
            },
            resetFormData() {
                this.formData = {
                    name: '',
                    comment: '',
                    id: ''
                };
            }
        },
        watch: {
            tierData() {
                console.log('+++++++++++++ tierData changed =', this.tierData);
                if (this.tierData && this.tierData.action === 'Add')
                {
                    this.resetFormData();
                    //this.formData.parent_id = this.tierData.data.id;
                    //this.formData.parent.name = this.tierData.data.name;
                    this.title = 'Adding a new tier';
                }
                else if (this.tierData && this.tierData.action === 'Edit')
                {
                    this.resetFormData();
                    this.title = 'Editing the tier';
                    this.formData.id = this.tierData.data.id;
                    this.formData.name = this.tierData.data.name;
                    this.formData.comment = this.tierData.data.comment;
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
