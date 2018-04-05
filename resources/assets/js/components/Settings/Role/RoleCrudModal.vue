<template>
    <div>
        <custom-modal :value="showModal" @cancel="onClose" effect="fade">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title"> {{ title }} </h4>
            </div>
            <div slot="modal-body" class="modal-body">
                <div class="form-group">
                    <bs-input label="Role Name" type="text" required  :maxlength="255" :icon="true" v-model="formRoleData.name"></bs-input>
                    <bs-input label="Parent Role Name"  type="text" disabled v-model="formRoleData.parent.name"></bs-input>
                    <div class="form-group" v-if="formRoleData.parent && formRoleData.parent.is_root">
                        <bs-checkbox v-model="formRoleData.is_admin" type="primary">Is Admin?</bs-checkbox>
                    </div>
                    <div class="form-group">
                        <bs-checkbox v-model="formRoleData.can_edit" type="primary">Can Edit?</bs-checkbox>
                    </div>
                    <bs-input label="Comment" type="textarea" :maxlength="255" :icon="true" v-model="formRoleData.comment"></bs-input>
                </div>
            </div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-success" @click="onClose">Cancel</button>
                <button type="button" class="btn btn-primary" @click="OnSave">Save</button>
            </div>
        </custom-modal>
    </div>
</template>

<script>
    import Vue from 'vue'
    import { mapState } from 'vuex'
    import modal from 'vue-strap/src/Modal'
    import input from 'vue-strap/src/Input'
    import checkbox from 'vue-strap/src/Checkbox'

    export default {
        computed: {
            ...mapState({
                showModal: state => state.role.showModal,
                roleData: state=> state.role.roleData,
            }),
        },
        data () {
            return {
                title: '',
                formRoleData: {
                    name: '',
                    parent: {name:'',id: ''},
                    comment: '',
                    can_edit: false,
                    is_admin: false,
                    is_root: false,
                    id: '',
                    parent_id: '',
                }
            }
        },
        created() {
            console.log('CustomModal Component created.')
        },
        components: {
            'custom-modal': modal,
            'bs-input': input,
            'bs-checkbox': checkbox
        },
        mounted() {
            console.log('CustomModal Component mounted. roleData=', this.roleData)
        },
        methods: {
            OnSave() {
                console.log('OnSave');
                let payload = {
                    isShow: false,
                    data: this.formRoleData,
                };
                if (this.isEmpty(this.formRoleData.name))
                {
                    this.$store.dispatch('showErrorNotification', 'Please input the role name!');
                    return;
                }
                if (this.roleData.action === 'Add')
                {
                    // new role
                    this.$store.dispatch('setRoleShowModal', payload);
                    this.$store.dispatch('addRoleRequest', this.formRoleData)
                        .then((response) => {})
                        .catch((error) => {});
                }
                else if (this.roleData.action === 'Edit')
                {
                    // update
                    this.$store.dispatch('setRoleShowModal', payload);
                    this.$store.dispatch('updateRoleRequest', this.formRoleData)
                        .then((response) => {})
                        .catch((error) => {});
                }
            },
            onClose() {
                console.log('onClose');
                let payload = {
                    isShow: false,
                    data: this.formRoleData,
                };
                this.$store.dispatch('setRoleShowModal', payload)
                this.resetFormData();
            },
            resetFormData() {
                this.formRoleData = {
                    name: '',
                    parent: {name:'',id: '', comment:'', can_edit: false, is_admin: false, is_root: false, parent_id: ''},
                    comment: '',
                    can_edit: false,
                    is_admin: false,
                    is_root: false,
                    id: '',
                    parent_id: '',
                };
            }
        },
        watch: {
            roleData() {
                console.log('+++++++++++++ roleData changed =', this.roleData);
                if (this.roleData && this.roleData.action === 'Add')
                {
                    this.resetFormData();
                    this.formRoleData.parent_id = this.roleData.data.id;
                    this.formRoleData.parent.name = this.roleData.data.name;
                    this.formRoleData.parent.is_root = this.roleData.data.is_root == '1' ? true: false;
                    this.title = 'Adding new role';
                }
                else if (this.roleData && this.roleData.action === 'Edit')
                {
                    this.resetFormData();
                    this.title = 'Editing role';
                    if (this.roleData.data.parent)
                    {
                        //this.formRoleData = Object.assign({}, this.roleData.data);
                        this.formRoleData = JSON.parse(JSON.stringify(this.roleData.data));
                        this.formRoleData.can_edit = this.formRoleData.can_edit == '1' ? true: false;
                        this.formRoleData.is_admin = this.formRoleData.is_admin == '1' ? true: false;
                        this.formRoleData.is_root = this.formRoleData.is_root == '1' ? true: false;
                        this.formRoleData.parent.is_root = this.formRoleData.parent.is_root == '1' ? true: false;
                        //this.formRoleData = { ...this.roleData.data };
                        // this.formRoleData = Vue.util.extend({}, this.roleData.data)
                    }
                    else
                    {
                        this.formRoleData.id = this.roleData.data.id;
                        this.formRoleData.name = this.roleData.data.name;
                        this.formRoleData.comment = this.roleData.data.comment;
                        this.formRoleData.can_edit = this.roleData.data.can_edit == '1' ? true: false;
                        this.formRoleData.is_admin = this.roleData.data.is_admin == '1' ? true: false;
                        this.formRoleData.is_root = this.roleData.data.is_root == '1' ? true: false;
                        this.formRoleData.parent_id = this.roleData.data.parent_id;
                    }
                }
            }
        }
    }
</script>

<style scoped>
    /*.modal-header {*/
        /*padding: 15px;*/
        /*border-bottom: 1px solid #e5e5e5;*/
        /*color: white !important;*/
        /*background-color: #0a5b9e !important;*/
        /*border-color: #0a5b9e;*/
        /*border-top-right-radius: 3px;*/
        /*border-top-left-radius: 3px;*/
    /*}*/
</style>
