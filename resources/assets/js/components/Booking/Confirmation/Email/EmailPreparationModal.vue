<template>
    <div>
        <div>
            <custom-modal :value="isShowModal" @cancel="onClickCancel" effect="fade" :backdrop="false">
                <div slot="modal-header" class="modal-header">
                    <h4 class="modal-title"> Email Confirmation </h4>
                </div>
                <div slot="modal-body" class="modal-body">
                    <email-preparation-body v-if="isShowModal"
                                            :order="order"
                                            :jobIds="jobIds"
                                            :emailType="emailType"
                                            ref="emailBody"></email-preparation-body>
                </div>
                <div slot="modal-footer" class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="onClickCancel">Cancel</button>
                    <button type="button" class="btn btn-success" @click="onClickSend">Send</button>
                </div>
            </custom-modal>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import modal from 'vue-strap/src/Modal'
    import EmailPreparationBody from './EmailPreparationBody.vue'

    export default {
        props: {
            isShowEmail: false,
            order: {
                type: Object,
            },
            jobIds: {
                type: Array,
            },
            emailType: {
                type: String,
            },
        },
        data () {
            return {
                isVisible: false,
            }
        },
        computed: {
            ...mapState({
                user: state => state.authUser,
            }),
            isShowModal() {
                this.isVisible = this.isShowEmail;
                return this.isVisible;
            }
        },
        created() {
            console.log('EmailPreparationModal Component created.')
        },
        components: {
            'email-preparation-body': EmailPreparationBody,
            'custom-modal': modal,
        },
        mounted() {
            console.log('EmailPreparationModal Component mounted.')
        },
        methods:
        {
            onClickSend() {
                console.log('onClickSend');
                if (this.isEmpty(this.$refs.emailBody.formData.emailTo))
                {
                    this.$store.dispatch('showErrorNotification', 'Please input the email address!');
                    return;
                }
                this.$emit('onCloseEmailModal');
                let formData = this.$refs.emailBody.formData;
                console.log('onClickSend formData=', formData);
                // SEND EMAIL REQUEST
                this.$store.dispatch('sendEmailRequest', formData)
                    .then((response) => {
                        console.log('sendEmailRequest response=', response);
                    })
                    .catch((error) => {
                        console.log('sendEmailRequest error=', error);
                    });
            },
            onClickCancel() {
                console.log('onClickCancel');
                this.$emit('onCloseEmailModal');
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


