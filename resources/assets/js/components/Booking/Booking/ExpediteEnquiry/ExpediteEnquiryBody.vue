<template>
    <div>
        <div>
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
                <div><label for="emailContent">Content</label></div>
                <expedite-quill-editor v-model="formData.emailContent"
                              ref="expediteQuillEditor"
                              :options="editorOption">
                </expedite-quill-editor>
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
            expediteType: '',
            order: {
                type: Object,
            },
            defaultSettings: {
                type: Array,
            }
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
                formData: {
                    templateType: '',
                    emailTo: '',
                    emailCc: '',
                    emailSubject: 'Expedite Enquiry',
                    emailContent: '',
                    order: this.order,
                    attachments: [],
                    type: this.expediteType? this.expediteType : 'EXPEDITE',
                },
            }
        },
        computed: {
            ...mapState({
                user: state => state.authUser,
            }),
        },
        created() {
            console.log('ExpediteEnquiryBody Component created.')
            if (this.user)
            {
                this.formData.emailCc = this.user.email;
            }
            this.formData.emailTo = this.getDefaulSetting('scheduler_email_address', this.defaultSettings);
            this.getExpediteEnquiryTemplate();
        },
        components: {
            'expedite-quill-editor': quillEditor,
        },
        mounted() {
            console.log('ExpediteEnquiryBody Component mounted.')
        },
        methods:
        {
            updateData: function (data) {
                console.log('updateData data=', data);
                // sync content to component
                this.formData.emailContent = data;
            },
            getExpediteEnquiryTemplate() {
                let payload = {
                    orderId: this.order.UDF1,
                    quoteId: this.order.QUOTE_ID,
                    location: this.order.QUOTE_NUM_PREF,
                };
                this.$store.dispatch('getExpediteEnquiryTemplate', payload)
                    .then((response) => {
                        console.log('getExpediteEnquiryTemplate response=', response);
                        this.formData.emailContent = response.body;
                    })
                    .catch((error) => {
                        console.log('getExpediteEnquiryTemplate error=', error);
                    });
            }
        }
    }
</script>

<style scoped>
    label {
        margin-top: 5px;
    }
</style>
