<template>
    <div>
        <custom-modal :value="showModal" @cancel="onClose" effect="fade">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title"> {{ title }} </h4>
            </div>
            <div slot="modal-body" class="modal-body">
                <div class="form-group">
                    <bs-input label="Customer Name" type="text" required  disabled :maxlength="255" :icon="true" v-model="formData.customer.name"></bs-input>
                    <bs-input label="Customer Code" type="text" required  disabled :maxlength="255" :icon="true" v-model="formData.customer.code"></bs-input>
                    <bs-input label="Customer Type Code" type="text" required  disabled :maxlength="255" :icon="true" v-model="formData.customer.classCode"></bs-input>
                    <div class="form-group">
                        <div><label for="Tier">Tier Level</label></div>
                        <Select clearable filterable v-model="formData.tierLevel"
                                placeholder="Please select a tier..."
                                style="width:180px"
                        >
                            <Option v-for="item in tierLevelOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
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
                showModal: state => state.customerTier.showModal,
                customerTierData: state=> state.customerTier.customerTierData,
            }),
        },
        props: {
        },
        data () {
            return {
                title: '',
                formData: {
                    customer: {name: '', code: '', classCode: '', id: ''},
                    tierLevel: '',
                    comment: '',
                    id: ''
                },
                tierLevelOptions: [],
            }
        },
        created() {
            console.log('CustomModal Component created.');
            this.$store.dispatch('getCustomerTierLevelOptions')
                .then((response) => {
                    console.log('created getCustomerTierLevelOptions success=', response);
                    this.setCustomerTierLevelOptions(response.data);
                })
                .catch((error) => {
                    console.error('getCustomerTierLevelOptions error=', error);
                });
        },
        components: {
            'custom-modal': modal,
            'bs-input': input,
        },
        mounted() {
            console.log('CustomModal Component mounted. customerTierData=', this.customerTierData)
        },
        methods: {
            setCustomerTierLevelOptions(levels) {
                console.log('setCustomerTierLevelOptions levels=',levels);
                let options = [];
                for (let level in levels) {
                    options.push({value: levels[level].id, label: levels[level].name});
                }
                this.tierLevelOptions = options;
            },
            OnSave() {
                console.log('OnSave');
                let payload = {
                    isShow: false,
                    data: this.formData,
                };
                if (this.customerTierData.action === 'Add')
                {
                    // new tab
                    this.$store.dispatch('setCustomerTierShowModal', payload);
                    this.$store.dispatch('addCustomerTierRequest', this.formData)
                        .then((response) => {
                            console.log('addCustomerTierRequest fire events -> refreshCustomerTierTable');
                            this.$events.fire('refreshCustomerTierTable');
                        })
                        .catch((error) => {});
                }
                else if (this.customerTierData.action === 'Edit')
                {
                    // update
                    this.$store.dispatch('setCustomerTierShowModal', payload);
                    this.$store.dispatch('updateCustomerTierRequest', this.formData)
                        .then((response) => {
                            console.log('updateCustomerTierRequest fire events -> refreshCustomerTierTable');
                            this.$events.fire('refreshCustomerTierTable');
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
                this.$store.dispatch('setCustomerTierShowModal', payload);
                this.resetFormData();
            },
            resetFormData() {
                this.formData = {
                    customer: {name: '', code: '', classCode: '', id: ''},
                    tierLevel: '',
                    comment: '',
                    id: ''
                };
            }
        },
        watch: {
            customerTierData() {
                console.log('+++++++++++++ customerTierData changed =', this.customerTierData);
                if (this.customerTierData && this.customerTierData.action === 'Add')
                {
                    this.resetFormData();
                    //this.formData.parent_id = this.customerTierData.data.id;
                    //this.formData.parent.name = this.customerTierData.data.name;
                    this.title = 'Adding a new customer tier';
                }
                else if (this.customerTierData && this.customerTierData.action === 'Edit')
                {
                    this.resetFormData();
                    this.title = 'Editing the customer tier';
                    this.formData.id = this.customerTierData.data.id;
                    this.formData.customer.name = this.customerTierData.data.CUST_NAME;
                    this.formData.customer.code = this.customerTierData.data.CUST_CODE;
                    this.formData.customer.classCode = this.customerTierData.data.CLASSIF_CODE;
                    this.formData.customer.id = this.customerTierData.data.CUST_ID;
                    this.formData.comment = this.customerTierData.data.comment;
                    this.formData.tierLevel = this.customerTierData.data.customer_tier_level? this.customerTierData.data.customer_tier_level.id : '';
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
