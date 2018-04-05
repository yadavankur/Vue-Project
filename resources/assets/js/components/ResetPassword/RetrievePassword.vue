<template>
    <div>
        <div>
            <custom-modal :value="isShowModal" :width="420" @cancel="onClickCancel" effect="fade" :backdrop="false">
                <div slot="modal-header" class="modal-header">
                    <h4 class="modal-title"> Retrieve Password </h4>
                </div>
                <div slot="modal-body" class="modal-body">
                    <div class="form-group">
                        Enter your email address and your password will be reset and emailed to you.
                        <Input placeholder="Email address" type="text" v-model="formData.email"></Input>
                    </div>
                </div>
                <div slot="modal-footer" class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="onClickCancel">Cancel</button>
                    <button type="button" class="btn btn-success" @click="onClickSend">Send Password Reset Link</button>
                </div>
            </custom-modal>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import modal from 'vue-strap/src/Modal'

    export default {
        props: {
            isShowModal: false,
        },
        data () {
            return {
                formData : {
                    email: ''
                }
            }
        },
        computed: {
        },
        created() {
            console.log('RetrievePasswordModal Component created.')
        },
        components: {
            'custom-modal': modal,
        },
        mounted() {
            console.log('RetrievePasswordModal Component mounted.')
        },
        methods: {
            onClickSend() {
                console.log('onClickSend');
                if (this.isEmpty(this.formData))
                {
                    this.$store.dispatch('showErrorNotification', 'Please input your email address!');
                    return;
                }
                // send save request
                console.log('onClickSend formData=', this.formData);
                // SEND EMAIL REQUEST
                this.$store.dispatch('retrievePasswordRequest', this.formData)
                    .then((response) => {
                        console.log('retrievePasswordRequest response=', response);
                        this.$store.dispatch('showSuccessNotification', response.body.message);
                        this.$emit('onCloseRetrievePasswordModal');
                    })
                    .catch((error) => {
                        console.log('retrievePasswordRequest error=', error);
                    });
            },
            onClickCancel() {
                console.log('onClickCancel');
                this.$emit('onCloseRetrievePasswordModal');
            }
        }
    }
</script>

<style scoped>
</style>