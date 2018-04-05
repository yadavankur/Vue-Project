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
                                            :attachments="attachments"
                                            @onSelectAttachment="onSelectAttachment"
                                            ref="emailBody">
                    </email-preparation-body>
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
            attachments: {
                type: Array,
            }
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
            onSelectAttachment() {
                console.log('onSelectAttachment');
                this.$emit('onSelectAttachment');
            },
            onClickSend() {
                console.log('onClickSend');
                this.$emit('onCloseEmailModal');
                let formData = this.$refs.emailBody.formData;
                if (formData.emailTo == '')
                {
                    this.$store.dispatch('showErrorNotification', 'Please input the customer email address!');
                    return;
                }
                formData.attachments = this.attachments;
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
</style>


