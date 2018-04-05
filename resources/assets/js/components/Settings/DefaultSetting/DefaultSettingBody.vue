<template>
    <div>
        <div>
            <div class="title-label">
                <div><label for="location">Location</label></div>
                <Select  v-model="formData.location_id"
                         :disabled="formData.id != ''"
                         @on-change="onChangeLocation"
                         placeholder="Please select a location..."
                >
                    <Option v-for="item in locationOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                </Select>
            </div>
            <bs-input label="Type " type="text" :disabled="formData.id != ''" :maxlength="255" v-model="formData.type"></bs-input>
            <bs-input label="Value " type="text" :maxlength="255" v-model="formData.value"></bs-input>
            <bs-input label="Description" type="textarea" :maxlength="255" :icon="true" v-model="formData.description"></bs-input>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import input from 'vue-strap/src/Input'

    export default {
        props: {
            itemData: {
                type: Object,
                default: null,
            },
            locationOptions: {
                type: Array,
                default: [],
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
                    id: '',
                    location_id: '',
                    type: '',
                    value: '',
                    description: '',
                },
            }
        },
        created() {
            console.log('DefaultSettingBody Component created.');
            if (this.isEmpty(this.itemData))
            {
                this.formData = {
                    id: '',
                    location_id: '',
                    type: '',
                    value: '',
                    description: '',
                };
            }
            else
            {
                this.formData = {
                    id: this.itemData.id,
                    location_id: parseInt(this.itemData.location_id),
                    type: this.itemData.type,
                    value: this.itemData.value,
                    description: this.itemData.description,
                };
            }
        },
        components: {
            'bs-input': input,
        },
        mounted() {
            console.log('DefaultSettingBody Component mounted.')
        },
        methods: {
            onChangeLocation(val) {
                console.log('onChangeLocation val=', val);
            },
        }
    }
</script>

<style scoped>
    label {
        margin-top: 5px;
    }
    .title-label {
        margin-bottom: 5px;
    }
</style>
