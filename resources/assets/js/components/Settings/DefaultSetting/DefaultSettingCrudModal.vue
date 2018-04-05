<template>
    <div>
        <div>
            <custom-modal :value="isShowDefaultSetting" @cancel="onClickCancel" effect="fade" :backdrop="false">
                <div slot="modal-header" class="modal-header">
                    <h4 class="modal-title"> {{ title }}</h4>
                </div>
                <div slot="modal-body" class="modal-body">
                    <default-setting-body v-if="isShowDefaultSetting"
                                    :itemData="itemData"
                                    :locationOptions="locationOptions"
                                    ref="defaultSettingBody">
                    </default-setting-body>
                </div>
                <div slot="modal-footer" class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="onClickCancel">Cancel</button>
                    <button type="button" class="btn btn-success" @click="onClickSave">Save</button>
                </div>
            </custom-modal>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import modal from 'vue-strap/src/Modal'
    import DefaultSettingBody from './DefaultSettingBody.vue'

    export default {
        props: {
            isShowDefaultSetting: false,
            title: '',
            actionType: '',
            itemData: {
                type: Object,
                default: null,
            },
            locationOptions: {
                type: Array,
                default: [],
            },
        },
        data () {
            return {
            }
        },
        computed: {
            ...mapState({
                user: state => state.authUser,
            }),
        },
        created() {
            console.log('DefaultSettingCrud Component created.')
        },
        components: {
            'default-setting-body': DefaultSettingBody,
            'custom-modal': modal,
        },
        mounted() {
            console.log('DefaultSettingCrud Component mounted.')
        },
        methods: {
            onClickSave() {
                console.log('onClickSave');
                if (this.isEmpty(this.$refs.defaultSettingBody.formData.type))
                {
                    this.$store.dispatch('showErrorNotification', 'Please input the type!');
                    return;
                }
                if (this.isEmpty(this.$refs.defaultSettingBody.formData.value))
                {
                    this.$store.dispatch('showErrorNotification', 'Please input the value!');
                    return;
                }
                let formData = this.$refs.defaultSettingBody.formData;
                formData.action_type = this.actionType;
                console.log('onClickSave formData=', formData);
                // SEND UPDATE REQUEST
                this.$store.dispatch('updateDefaultSettingRequest', formData)
                    .then((response) => {
                        console.log('updateDefaultSettingRequest response=', response);
                        this.$emit('onCloseDefaultSettingModal');
                        this.$events.fire('refreshDefaultSettingListTable')
                    })
                    .catch((error) => {
                        console.log('updateDefaultSettingRequest error=', error);
                    });
            },
            onClickCancel() {
                console.log('onClickCancel');
                this.$emit('onCloseDefaultSettingModal');
            }
        }
    }
</script>

<style scoped>
</style>