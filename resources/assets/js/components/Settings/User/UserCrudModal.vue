<template>
    <div>
        <custom-modal :value="showModal" @cancel="onClose" effect="fade">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title"> {{ title }} </h4>
            </div>
            <div slot="modal-body" class="modal-body">
                <div class="form-group">
                    <bs-input label="User Name" type="text" required  :maxlength="255" :icon="true" v-model="formData.name"></bs-input>
                    <bs-input label="Email" type="email" required  :maxlength="255" :icon="true" v-model="formData.email"></bs-input>
                    <div class="role-group-panel">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <a>
                                    <strong>Role / Group</strong>
                                </a>
                                <span class="btn btn-del btn-primary btn-xs pull-right" title="New" @click="onClickNewRoleGroup()">
                                    <span class="glyphicon glyphicon-plus"></span>
                                    NEW
                                </span>
                            </div>
                            <div class="list-div ">
                                <table class="table table-condensed">
                                    <tbody>
                                    <tr is="user-role-group-selection"
                                        v-for="(rolegroup, index) in rolegroups"
                                        :key="rolegroup"
                                        :selectedRole="rolegroup.role"
                                        :selectedGroup="rolegroup.group"
                                        :row-data="rolegroup"
                                        :row-index="index"
                                        :options="options"
                                        @onDelete="onDeleteRoleGroup"
                                    >
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <bs-input label="New Password" type="password" :maxlength="255"
                              :icon="true" v-model="formData.password">
                    </bs-input>
                    <bs-input label="Confirm Password" type="password" :maxlength="255"
                              :match="formData.password"
                              error="Passwords do NOT match!"
                              :icon="true" v-model="formData.confirmPassword">
                    </bs-input>
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
    import UserRoleGroupSelection from './UserRoleGroupSelection.vue'


    export default {
        props: {
            options: {
                type: Array,
                default: [],
            },
        },
        computed: {
            ...mapState({
                showModal: state => state.user.showModal,
                userData: state=> state.user.userData,
            }),
        },
        data () {
            return {
                title: '',
                formData: {
                    name: '',
                    email: '',
                    password: '',
                    confirmPassword:'',
                    group:'',
                    role:'',
                    id: '',
                    rolegroups: [],
                },
                rolegroups: [],
                selectedRole: '',
                selectedGroup: '',
                //cascadeRolesGroups: [],
            }
        },
        created() {
            console.log('CustomModal Component created.')

        },
        components: {
            'custom-modal': modal,
            'bs-input': input,
            'user-role-group-selection': UserRoleGroupSelection,
        },
        mounted() {
            console.log('CustomModal Component mounted. userData=', this.userData)
        },
        methods: {
            onDeleteRoleGroup(data) {
                console.log('CustomModal onDeleteRoleGroup', data);
                this.rolegroups.splice(data.index, 1);
            },
            onClickNewRoleGroup() {
                console.log('onClickNewRoleGroup');
                this.rolegroups.push({role: '', group: ''});
                console.log('userData ====== this.rolegroups=', this.rolegroups);
            },
            OnSave() {
                console.log('OnSave');
                let payload = {
                    isShow: false,
                    data: this.formData,
                };
                if (this.isEmpty(this.formData.name))
                {
                    this.$store.dispatch('showErrorNotification', 'Please input the user name!');
                    return;
                }
                if (this.isEmpty(this.formData.email))
                {
                    this.$store.dispatch('showErrorNotification', 'Please input the email address!');
                    return;
                }
                if (this.formData.rolegroups.length == 0)
                {
                    this.$store.dispatch('showErrorNotification', 'Please assign at least one role to the user!');
                    return;
                }
                if (this.userData.action === 'Add')
                {
                    // new state
                    this.$store.dispatch('setUserShowModal', payload);
                    this.$store.dispatch('addUserRequest', this.formData)
                        .then((response) => {
                            console.log('addUserRequest fire events -> refreshUserTable');
                            this.$events.fire('refreshUserTable');
                        })
                        .catch((error) => {});
                }
                else if (this.userData.action === 'Edit')
                {
                    // update
                    this.$store.dispatch('setUserShowModal', payload);
                    this.$store.dispatch('updateUserRequest', this.formData)
                        .then((response) => {
                            console.log('updateUserRequest fire events -> refreshUserTable');
                            this.$events.fire('refreshUserTable');
                        })
                        .catch((error) => {});
                }
            },
            onClose() {
                console.log('onClose');
                this.resetFormData();
                let payload = {
                    isShow: false,
                    data: this.formData,
                };
                this.formData.rolegroups = [];
                this.rolegroups = this.formData.rolegroups;
                this.$store.dispatch('setUserShowModal', payload);
            },
            resetFormData() {
                this.formData = {
                    name: '',
                    email: '',
                    password: '',
                    confirmPassword: '',
                    id: '',
                    rolegroups: [],
                };
            }
        },
        watch: {
            userData() {
                console.log('+++++++++++++ userData changed =', this.userData);
                this.selectedRole = this.userData.selectedRole;
                if (this.userData && this.userData.action === 'Add')
                {
                    this.resetFormData();
                    this.title = 'Adding a new user';
                    this.formData.rolegroups = [];
                    this.rolegroups = this.formData.rolegroups;
                }
                else if (this.userData && this.userData.action === 'Edit')
                {
                    this.resetFormData();
                    this.title = 'Editing the user';
                    this.formData.id = this.userData.data.id;
                    this.formData.name = this.userData.data.name;
                    this.formData.email = this.userData.data.email;
                    this.formData.password = '';
                    this.formData.confirmPassword = '';
                    let usergroups = this.userData.data.usergroups;
                    let rolegroups = [];
                    for (let usergroup in usergroups) {
                        if (!usergroups[usergroup].group || !usergroups[usergroup].group.role) break;
                        rolegroups.push({role: usergroups[usergroup].group.role, group: usergroups[usergroup].group});
                    }
                    this.formData.rolegroups = rolegroups;
                    this.rolegroups = rolegroups;
                    console.log('userData ====== this.formData.rolegroups=', this.formData.rolegroups);
                }
            }
        }
    }
</script>

<style scoped>
    .table {
        margin-bottom: 10px !important;
    }
    .panel-heading a {
        color: black;
    }
</style>
