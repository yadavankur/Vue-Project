<template>
    <div>
        <custom-modal :value="showModal" @cancel="onClose" effect="fade">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title"> {{ title }} </h4>
            </div>
            <div slot="modal-body" class="modal-body">
                <div class="form-group">
                    <bs-input label="State Name" type="text" required  :maxlength="255" :icon="true" v-model="formData.name"></bs-input>
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
                showModal: state => state.state.showModal,
                stateData: state=> state.state.stateData,
            }),
        },
        data () {
            return {
                title: '',
                formData: {
                    name: '',
                    comment: '',
                    id: '',
                }
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
            console.log('CustomModal Component mounted. stateData=', this.stateData)
        },
        methods: {
            OnSave() {
                console.log('OnSave');
                let payload = {
                    isShow: false,
                    data: this.formData,
                };
                if (this.stateData.action === 'Add')
                {
                    // new state
                    this.$store.dispatch('setStateShowModal', payload);
                    this.$store.dispatch('addStateRequest', this.formData)
                        .then((response) => {})
                        .catch((error) => {});
                }
                else if (this.stateData.action === 'Edit')
                {
                    // update
                    this.$store.dispatch('setStateShowModal', payload);
                    this.$store.dispatch('updateStateRequest', this.formData)
                        .then((response) => {})
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
                this.$store.dispatch('setStateShowModal', payload);
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
            stateData() {
                console.log('+++++++++++++ stateData changed =', this.stateData);
                if (this.stateData && this.stateData.action === 'Add')
                {
                    this.resetFormData();
                    this.title = 'Adding a new state';
                }
                else if (this.stateData && this.stateData.action === 'Edit')
                {
                    this.resetFormData();
                    this.title = 'Editing the state';
                    this.formData.id = this.stateData.data.id;
                    this.formData.name = this.stateData.data.name;
                    this.formData.comment = this.stateData.data.comment;
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
