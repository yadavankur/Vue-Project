<template>
    <div>
        <div>
            <div class="delivery-address">
                <div><label for="address1">Address 1</label></div>
                <Input v-model="formData.address1" placeholder="Please input the address..."></Input>
            </div>
            <div class="delivery-address">
                <div><label for="address2">Address 2</label></div>
                <Input v-model="formData.address2" placeholder="Please input the address..."></Input>
            </div>
            <div class="delivery-address">
                <div><label for="address2">Suburb</label></div>
                <Input v-model="formData.address3" placeholder="Please input the address..."></Input>
            </div>
            <div class="delivery-address">
                <div><label for="address2">State</label></div>
                <Select v-model="formData.address4">
                    <Option v-for="item in stateOptions" :value="item.value" :key="item.value">{{ item.label }}</Option>
                </Select>
            </div>
            <div class="delivery-address">
                <div><label for="address2">Post Code</label></div>
                <Input v-model="formData.address5" placeholder="Please input the address..."></Input>
            </div>
            <div class="delivery-address">
                <div><label for="address2">Country</label></div>
                <Select v-model="formData.address6">
                    <Option v-for="item in countryOptions" :value="item.value" :key="item.value">{{ item.label }}</Option>
                </Select>
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
                    address1: '',   //
                    address2: '',   //
                    address3: '',   // suburb
                    address4: '',   // state
                    address5: '',   // post code
                    address6: '',   // country
                },
                countryOptions: [
                    {value: 'Australia', label: 'Australia'}
                ],
                stateOptions: [
                    {value: 'ACT', label: 'ACT'},
                    {value: 'NSW', label: 'NSW'},
                    {value: 'NT', label: 'NT'},
                    {value: 'QLD', label: 'QLD'},
                    {value: 'SA', label: 'SA'},
                    {value: 'TAS', label: 'TAS'},
                    {value: 'VIC', label: 'VIC'},
                    {value: 'WA', label: 'WA'},
                ],
            }
        },
        created() {
            console.log('ChangeAddressBody Component created. order=', this.order);
            if (this.order && this.order.deliver_address)
            {
                this.formData = {
                    address1: this.order.deliver_address.ADDR_1,
                    address2: this.order.deliver_address.ADDR_2,   //
                    address3: this.order.deliver_address.ADDR_3,   // suburb
                    address4: this.order.deliver_address.ADDR_4,   // state
                    address5: this.order.deliver_address.ADDR_5,   // post code
                    address6: this.order.deliver_address.ADDR_6,   // country
                }
            }
        },
        components: {
        },
        mounted() {
            console.log('ChangeAddressBody Component mounted.')
        },
        methods: {
        }
    }
</script>

<style scoped>
</style>
