<template>
    <div>
        <custom-modal :value="showModal" @cancel="onClose" effect="fade">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title"> {{ title }} </h4>
            </div>
            <div slot="modal-body" class="modal-body">
                <div class="form-group">
                    <bs-input label="Group Name" type="text" required  :maxlength="255" :icon="true" v-model="formData.name"></bs-input>
                    <div class="form-group">
                        <div><label for="role">Role</label></div>
                        <Select clearable filterable labelInValue v-model="formData.role.id"
                                @on-change="onRoleChanged"
                                size="large"
                                placeholder="Please select a role..."
                        >
                            <Option v-for="item in roleOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                        </Select>
                    </div>
                    <bs-input label="Comment" type="textarea" :maxlength="255" :icon="true" v-model="formData.comment"></bs-input>
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

    export default {
        computed: {
            ...mapState({
                showModal: state => state.group.showModal,
                groupData: state=> state.group.groupData,
            }),
        },
        props: {
        },
        data () {
            return {
                title: '',
                formData: {
                    name: '',
                    comment: '',
                    id: '',
                    role_id: '',
                    role: {name:'', id:''}
                },
                defaultRole: '',
                roleOptions: [],
                minSearch: 1,
            }
        },
        created() {
            console.log('GroupCustomModal Component created.');
            // get role list
            this.$store.dispatch('getAllRoleOptions')
                .then((response) => {
                    console.log('created getAllRoleOptions success=', response);
                    this.setRoleOptions(response.data.roles);
                })
                .catch((error) => {
                        console.error('getAllRoleOptions error=', error);
                });
        },
        components: {
            'custom-modal': modal,
            'bs-input': input,
        },
        mounted() {
            console.log('CustomModal Component mounted. groupData=', this.groupData)
        },
        methods: {
            onRoleChanged(selVal)
            {
                if (!selVal) return;
//                if (!selVal.value || !selVal.label) return;
                this.formData.role.name = selVal.label;
            },
            setRoleOptions(roles) {
                console.log('setRoleOptions roles=',roles);
                let options = [];
                for (let role in roles) {
                    options.push({value: parseInt(roles[role].id), label: roles[role].name});
                }
                this.roleOptions = options;
                //this.defaultRole = this.roleOptions[0].value;
            },
            OnSave() {
                console.log('OnSave');
                let payload = {
                    isShow: false,
                    data: this.formData,
                };
                if (this.groupData.action === 'Add')
                {
                    // new tab
                    this.$store.dispatch('setGroupShowModal', payload);
                    this.$store.dispatch('addGroupRequest', this.formData)
                        .then((response) => {
                            console.log('addGroupRequest fire events -> refreshGroupTable');
                            this.$events.fire('refreshGroupTable');
                        })
                        .catch((error) => {});
                }
                else if (this.groupData.action === 'Edit')
                {
                    // update
                    this.$store.dispatch('setGroupShowModal', payload);
                    this.$store.dispatch('updateGroupRequest', this.formData)
                        .then((response) => {
                            console.log('updateGroupRequest fire events -> refreshGroupTable');
                            this.$events.fire('refreshGroupTable');
                        })
                        .catch((error) => {});
                }
                else
                {
                    // error
                }
            },
            onClose() {
                console.log('onClose');
                let payload = {
                    isShow: false,
                    data: this.formData,
                };
                this.$store.dispatch('setGroupShowModal', payload);
                this.resetFormData();
            },
            resetFormData() {
                this.formData = {
                    name: '',
                    comment: '',
                    id: '',
                    role_id: '',
                    role: {name:'', id:''},
                };
            }
        },
        watch: {
            groupData() {
                console.log('+++++++++++++ groupData changed =', this.groupData);

                if (this.groupData && this.groupData.action === 'Add')
                {
                    this.resetFormData();
                    this.title = 'Adding a new group';
                }
                else if (this.groupData && this.groupData.action === 'Edit')
                {
                    this.resetFormData();
                    this.title = 'Editing the group';
                    this.formData.id = this.groupData.data.id;
                    this.formData.name = this.groupData.data.name;
                    this.formData.comment = this.groupData.data.comment;
                    this.formData.role.id = this.groupData.data.role.id;
                    this.formData.role.name = this.groupData.data.role.name;
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
