<template>
    <div>
        <div class="expediteModal">
            <custom-modal :value="isShowModal" @cancel="onClickCancel" effect="fade" :backdrop="false">
                <div slot="modal-header" class="modal-header">
                    <h4 class="modal-title"> {{ expediteType == 'EXPEDITE_FEEDBACK'? 'Expedite Feedback' : 'Expedite Enquiry' }} </h4>
                </div>
                <div slot="modal-body" class="modal-body">
                    <expedite-enquiry-body :expediteType="expediteType"
                                           :order="order"
                                           v-if="isShowModal"
                                           :defaultSettings="defaultSettings"
                                           ref="expediteBody"></expedite-enquiry-body>
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
    import modal from 'vue-strap/src/Modal'
    import ExpediteEnquiryBody from './ExpediteEnquiryBody.vue'
    export default {
        props: {
            isShowExpediteEnquiry: false,
            expediteType: '',
            order: {  type: Object,   },
        },
        data () {
            return {
                isVisible: false,
                defaultSettings: null,
            }
        },
        computed: {
            isShowModal() {
                this.isVisible = this.isShowExpediteEnquiry;
                return this.isVisible;
            }
        },
        created() {
            console.log('ExpediteEnquiryModal Component created.')
            // get default settings
            let payload = {
                location: this.order.QUOTE_NUM_PREF,
            };
            this.getLocationDefaultSettings(payload);
        },
        components: {
            'expedite-enquiry-body': ExpediteEnquiryBody,
            'custom-modal': modal,
        },
        mounted() {
            console.log('ExpediteEnquiryModal Component mounted.')
        },
        methods:
        {
            onClickSend() {
                console.log('onClickSend');
                this.$emit('onCloseExpediteEnquiryModal');
                let formData = this.$refs.expediteBody.formData;
                if (formData.emailTo == '')
                {
                    this.$store.dispatch('showErrorNotification', "Please input the scheduler's email address!");
                    return;
                }
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
                this.$emit('onCloseExpediteEnquiryModal');
            }
        }
    }
</script>

<style scoped>
    .expediteModal {
        position: fixed;
        z-index: 1500;
    }
</style>


