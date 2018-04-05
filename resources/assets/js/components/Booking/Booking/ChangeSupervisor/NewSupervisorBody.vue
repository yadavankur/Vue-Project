<template>
    <div>
        <div>
            <div class="supervisor-contact">
                <div><label for="contactName">Contact Name</label></div>
                <Input v-model="formData.contactName" placeholder="Please input the contact name..."></Input>
            </div>
            <div class="supervisor-contact">
                <div><label for="emailAddress">Email Address</label></div>
                <Input v-model="formData.emailAddress" placeholder="Please input the email address..."></Input>
            </div>
            <div class="supervisor-contact">
                <div><label for="phone">Phone</label></div>
                <Input v-model="formData.phone" placeholder="Please input the phone number..."></Input>
            </div>
            <div class="supervisor-contact">
                <div><label for="mobile">Mobile</label></div>
                <Input v-model="formData.mobile" placeholder="Please input the mobile number..."></Input>
            </div>
            <div class="supervisor-contact">
                <div><label for="fax">Fax</label></div>
                <Input v-model="formData.fax" placeholder="Please input the fax number..."></Input>
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
                formData: {
                    contactName: '',
                    emailAddress: '',
                    phone: '',
                    fax: '',
                    mobile: '',
                },
            }
        },
        created() {
            console.log('NewSupervisorBody Component created.');
        },
        components: {
        },
        mounted() {
            console.log('NewSupervisorBody Component mounted.')
        },
        methods:
        {
        }
    }
</script>

<style scoped>
    label {
        margin-top: 5px;
    }
</style>
