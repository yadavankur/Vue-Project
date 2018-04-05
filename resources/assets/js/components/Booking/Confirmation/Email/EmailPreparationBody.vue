<template>
    <div>
        <div>
            <div class="email-label" v-if="emailType == 'single'">
                <div><label for="templateType">Email Template Type</label></div>
                <Select  v-model="formData.optionType"
                        @on-change="onChangeEmailTemplateType"
                        placeholder="Please select an email template type..."
                >
                    <Option v-for="item in templateOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                </Select>
            </div>
            <div class="email-content">
                <div><label for="emailTo">To</label></div>
                <Input v-model="formData.emailTo" placeholder="Please input the email address..."></Input>
            </div>
            <div class="email-content">
                <div><label for="emailCc">Cc</label></div>
                <Input v-model="formData.emailCc" placeholder="Please input the email address..."></Input>
            </div>
            <div class="email-content">
                <div><label for="emailSubject">Subject</label></div>
                <Input v-model="formData.emailSubject" placeholder="Please input the Subject..."></Input>
            </div>
            <div class="email-content" v-if="emailType == 'list'">
                <div>
                    <label for="templateType">
                        <button type="button" class="btn btn-primary btn-xs"
                                title="view attachment"
                                @click="onViewAttachment">
                            <i class="glyphicon glyphicon-eye-open"> View</i>
                        </button>
                    </label>
                </div>
            </div>
            <div class="email-content">
                <div><label for="emailContent">Content</label></div>
                <email-quill-editor v-model="formData.emailContent"
                              ref="emailQuillEditor"
                              :options="editorOption">
                </email-quill-editor>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import { quillEditor } from 'vue-quill-editor'

    export default {
        props: {
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
        computed: {
            ...mapState({
                user: state => state.authUser,
                //selectedOrder: state => state.tab.selectedOrder,
            }),
        },
        data () {
            return {
                editorOption: {
                    //readOnly: true,
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            [{ 'indent': '-1'}, { 'indent': '+1' }],
                            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                            [{ 'color': [] }, { 'background': [] }],
                            [{ 'font': [] }],
                            [{ 'align': [] }],
                            ['link', 'image']
                        ],
                    },
                    placeholder: 'Please input email content...',
                    theme: 'snow'
                },
                templateOptions: [
                    { value: 'SINGLE_EMAIL_TELEPHONE_CALL', label: 'To confirm a telephone call' },
                    { value: 'SINGLE_EMAIL_REQUEST_CALL', label: 'To request a call to confirm' },
                ],
                formData: {
                    templateType: 3, // Email::TEMPLATE_SINGLE_CONFIRMATION
                    optionType: 'SINGLE_EMAIL_TELEPHONE_CALL',
                    emailTo: '',
                    emailCc: '',
                    emailSubject: 'Order Confirmation',
                    emailContent: '',
                    order: this.order,
                    attachments: [],
                    type: 'EXTERNAL',
                    selectedJobIds: this.jobIds,
                },
            }
        },
        created() {
            console.log('EmailPreparationBody Component created.');
            if (this.order)
            {
                this.formData.emailTo = this.order.customer.EMAIL_ADDR;
                this.formData.order = this.order;
            }
            if (this.user)
            {
                this.formData.emailCc = this.user.email;
            }
            //this.getSingleConfirmationEmailTemplate(this.formData.optionType);
            if (this.emailType == 'list')
            {
                this.formData.templateType = 4;
                this.formData.optionType = 'LIST_EMAIL_TELEPHONE_CALL';
            }

            this.getConfirmationEmailTemplate(this.formData.optionType);
        },
        components: {
            'email-quill-editor': quillEditor,
        },
        mounted() {
            console.log('EmailPreparationBody Component mounted.')
        },
        methods:
        {
            onViewAttachment() {
                console.log('onViewAttachment');
                this.getEmailListPdf();
            },
            onChangeEmailTemplateType(value) {
                console.log('onChangeEmailTemplateType value=', value);
                this.formData.templateType = 3;
                this.getConfirmationEmailTemplate(value);
            },
            updateData: function (data) {
                console.log('updateData data=', data);
                // sync content to component
                this.formData.emailContent = data;
            },
            getConfirmationEmailTemplate(emailType) {
                let payload = {
                    emailType: emailType,
                    quoteId: this.order.QUOTE_ID,
                    location: this.order.QUOTE_NUM_PREF,
                    orderId: this.order.UDF1,
                };
                this.$store.dispatch('getConfirmationEmailTemplate', payload)
                    .then((response) => {
                    console.log('getConfirmationEmailTemplate response=', response);
                    this.formData.emailContent = response.body;
                })
                .catch((error) => {
                        console.log('getConfirmationEmailTemplate error=', error);
                });
            },
            getEmailListPdf() {
                let payload = {
                    quoteId: this.order? this.order.QUOTE_ID: '',
                    location: this.order? this.order.QUOTE_NUM_PREF : '',
                    orderId: this.order? this.order.UDF1: '',
                    selectedJobIds: this.jobIds,
                    viewType: 'EMAIL_LIST',
                };
                this.$store.dispatch('getPrintConfirmationList', payload)
                    .then((response) => {
                        console.log('getPrintConfirmationList response=', response);
                        this.downLoad(response);
                    })
                    .catch((error) => {
                        console.log('getPrintConfirmationList error=', error);
                    });
            },
        }
    }
</script>

<style scoped>
    label {
        margin-top: 5px;
    }
</style>
