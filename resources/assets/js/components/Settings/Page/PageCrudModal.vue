<template>
    <div>
        <custom-modal :value="showModal" @cancel="onClose" effect="fade">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title"> {{ title }} </h4>
            </div>
            <div slot="modal-body" class="modal-body">
                <div class="form-group">
                    <bs-input label="Page Name" type="text" required  :maxlength="255" :icon="true" v-model="formData.name"></bs-input>
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
                showModal: state => state.page.showModal,
                pageData: state=> state.page.pageData,
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
            console.log('CustomModal Component mounted. pageData=', this.pageData)
        },
        methods: {
            OnSave() {
                console.log('OnSave');
                let payload = {
                    isShow: false,
                    data: this.formData,
                };
                if (this.pageData.action === 'Add')
                {
                    // new tab
                    this.$store.dispatch('setPageShowModal', payload);
                    this.$store.dispatch('addPageRequest', this.formData)
                        .then((response) => {
                            console.log('addPageRequest fire events -> refreshPageTable');
                            this.$events.fire('refreshPageTable');
                        })
                        .catch((error) => {});
                }
                else if (this.pageData.action === 'Edit')
                {
                    // update
                    this.$store.dispatch('setPageShowModal', payload);
                    this.$store.dispatch('updatePageRequest', this.formData)
                        .then((response) => {
                            console.log('updatePageRequest fire events -> refreshPageTable');
                            this.$events.fire('refreshPageTable');
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
                this.$store.dispatch('setPageShowModal', payload);
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
            pageData() {
                console.log('+++++++++++++ pageData changed =', this.pageData);
                if (this.pageData && this.pageData.action === 'Add')
                {
                    this.resetFormData();
                    this.title = 'Adding a new page';
                }
                else if (this.pageData && this.pageData.action === 'Edit')
                {
                    this.resetFormData();
                    this.title = 'Editing the page';
                    this.formData.id = this.pageData.data.id;
                    this.formData.name = this.pageData.data.name;
                    this.formData.comment = this.pageData.data.comment;
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
