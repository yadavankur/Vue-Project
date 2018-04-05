<template>
    <div>
        <div>
            <div class="text-label">
                <div><label for="templateType">Text Message Template</label></div>
                <Select  v-model="formData.optionType"
                         @on-change="onChangeTextTemplateType"
                         placeholder="Please select a text message template..."
                >
                    <Option v-for="item in templateOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                </Select>
            </div>
            <div class="text-content">
                <div><label for="textTo">To</label></div>
                <Input v-model="formData.emailTo" placeholder="Please input the phone number...">
                    <span slot="append">@messagenet.com.au</span>
                </Input>
            </div>
            <div class="text-content">
                <div><label for="textMessage">Text Message</label></div>
                <Input v-model="formData.emailContent"
                       type="textarea" :autosize="{minRows: 5,maxRows: 8}"
                       placeholder="Please input the text message...">
                </Input>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    export default {
        props: {
            order: {
                type: Object,
            },
        },
        computed: {
            ...mapState({
                user: state => state.authUser,
            }),
        },
        data () {
            return {
                templateOptions: [
                    { value: 'SINGLE_TEXT_ORDER_CONFIRMATION', label: 'For Order Confirmation' },
                    { value: 'SINGLE_TEXT_REQUEST_CALL', label: 'To request a call' },
                ],
                formData: {
                    templateType: 2, // not used for text message
                    optionType: 'SINGLE_TEXT_ORDER_CONFIRMATION',
                    emailTo: '',
                    emailCc: '',
                    emailSubject: '',
                    emailContent: '',
                    attachments: [],
                    order: this.order,
                    type: 'TEXT_MESSAGE',
                },
            }
        },
        created() {
            console.log('TextMessageBody Component created.');
            if (this.order)
            {
                this.formData.emailTo = this.order.customer.MOB_PHONE;
                this.formData.order = this.order;
            }
            this.getSingleConfirmationTextTemplate(this.formData.optionType);
        },
        components: {
        },
        mounted() {
            console.log('TextMessageBody Component mounted.')
        },
        methods:
        {
            onChangeTextTemplateType(value) {
                console.log('onChangeTextTemplateType value=', value);
                this.getSingleConfirmationTextTemplate(value);
            },
            getSingleConfirmationTextTemplate(textType) {
                let payload = {
                    textType: textType,
                    orderId: this.order.UDF1,
                };
                this.$store.dispatch('getSingleConfirmationTextTemplate', payload)
                    .then((response) => {
                        console.log('getSingleConfirmationEmailTemplate response=', response);
                        this.formData.emailContent = response.body;
                    })
                    .catch((error) => {
                            console.log('getSingleConfirmationEmailTemplate error=', error);
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
