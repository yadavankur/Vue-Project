<template>
    <div>
        <div class="newSupervisor">
            <custom-modal :value="isShowModal" @cancel="onClickCancel" effect="fade" :backdrop="false">
                <div slot="modal-header" class="modal-header">
                    <h4 class="modal-title"> Creating a new Supervisor </h4>
                </div>
                <div slot="modal-body" class="modal-body">
                    <new-supervisor-body v-if="isShowModal" ref="newSupervisor"></new-supervisor-body>
                </div>
                <div slot="modal-footer" class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="onClickCancel">Cancel</button>
                    <button type="button" class="btn btn-success" @click="onClickSave">Save</button>
                </div>
            </custom-modal>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import modal from 'vue-strap/src/Modal'
    import NewSupervisorBody from './NewSupervisorBody.vue'
    export default {
        props: {
            isShowNewSupervisor: false,
            order: null,
        },
        data () {
            return {
                isVisible: false,
            }
        },
        computed: {
            isShowModal() {
                this.isVisible = this.isShowNewSupervisor;
                return this.isVisible;
            },
            ...mapState({
                user: state => state.authUser,
            }),
        },
        created() {
            console.log('NewSupervisorModal Component created.')
        },
        components: {
            'new-supervisor-body': NewSupervisorBody,
            'custom-modal': modal,
        },
        mounted() {
            console.log('NewSupervisorModal Component mounted.')
        },
        methods:
            {
                onClickSave() {
                    console.log('onClickSave');
                    // send save request
                    let formData = {
                        quoteId: this.order? this.order.QUOTE_ID: '',
                        quoteVers: this.order? this.order.QUOTE_VERS: '',
                        customerId: this.order? this.order.CUST_ID: '',
                        contactName: this.$refs.newSupervisor.formData.contactName,
                        phone: this.$refs.newSupervisor.formData.phone,
                        emailAddress: this.$refs.newSupervisor.formData.emailAddress,
                        fax: this.$refs.newSupervisor.formData.fax,
                        mobile: this.$refs.newSupervisor.formData.mobile,
                    };
                    console.log('onClickSave formData=', formData);
                    // SEND EMAIL REQUEST
                    this.$store.dispatch('newSupervisorRequest', formData)
                        .then((response) => {
                            console.log('newSupervisorRequest response=', response);
                            // close super visor list modal
                            // and refresh booking page
                            this.$emit('onCloseNewSupervisorModal', 'newSuccess');
                        })
                        .catch((error) => {
                            console.log('newSupervisorRequest error=', error);
                        });
                },
                onClickCancel() {
                    console.log('onClickCancel');
                    this.$emit('onCloseNewSupervisorModal');
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
    .newSupervisor {
        position: fixed;
        z-index: 1200;
    }
</style>


