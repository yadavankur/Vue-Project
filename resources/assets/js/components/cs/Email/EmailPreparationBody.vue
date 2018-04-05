<template>
    <div>
        <div>
            <!--<div class="email-label">-->
                <!--<div><label for="templateType">Email Template Type</label></div>-->
                <!--<Select  v-model="formData.templateType"-->
                        <!--@on-change="onChangeEmailTemplateType"-->
                        <!--placeholder="Please select an email template type..."-->
                <!--&gt;-->
                    <!--<Option v-for="item in templateOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>-->
                <!--</Select>-->
            <!--</div>-->
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
            <div class="email-content">
                <div><label for="templateType"><button type="button" class="btn btn-primary btn-xs"
                                                       title="Attachments"
                                                       @click="onSelectAttachment">
                    <i class="glyphicon glyphicon-plus">Attachments</i>
                </button></label></div>
                <div v-if="attachments && (attachments.length > 0)">
                    <Input type="textarea"
                           :value="attachments.map(function(a) {return a.fileName;}).join(';')"
                           autosize
                           disabled></Input>
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
            attachments: {
              type: Array,
            }
        },
        computed: {
            ...mapState({
                user: state => state.authUser,
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
                    { value: 1, label: 'Order Confirmation' },
                    { value: 2, label: 'Site Measure Confirmation' },
                    { value: 3, label: 'Delivery Confirmation' },
                    { value: 4, label: 'Invoice' },
                ],
                formData: {
                    templateType: 1,
                    emailTo: '',
                    emailCc: '',
                    emailSubject: 'Order Confirmation',
                    emailContent: '',
                    order: this.order,
                    attachments: this.attachments,
                    type: 'EXTERNAL',
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
            // get email template based on customer type
            let customerType = 'AC'; // account customer
            if (this.order && this.order.customer && (this.order.customer.ACCOUNT_TYPE == 'CS'))
            {
                // cash customer
                customerType = this.order.customer.ACCOUNT_TYPE;
            }
            let payload = {
                customerType: customerType,
                orderId: this.order.UDF1,
                quoteId: this.order.QUOTE_ID,
                location: this.order.QUOTE_NUM_PREF,
            };
            this.$store.dispatch('getOrderConfirmationEmailTemplate', payload)
                .then((response) => {
                    console.log('getOrderConfirmationEmailTemplate response=', response);
                    this.formData.emailContent = response.body;
                })
                .catch((error) => {
                        console.log('getOrderConfirmationEmailTemplate error=', error);
                });
        },
        components: {
            'email-quill-editor': quillEditor,
        },
        mounted() {
            console.log('EmailPreparationBody Component mounted.')
        },
        methods:
        {
            onSelectAttachment()
            {
                console.log('onSelectAttachment');
                this.$emit('onSelectAttachment');
            }
        }
    }
</script>

<style scoped>
    label {
        margin-top: 5px;
    }
</style>
