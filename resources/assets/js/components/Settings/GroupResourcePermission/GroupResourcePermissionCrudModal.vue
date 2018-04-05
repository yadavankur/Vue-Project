<template>
    <div>
        <custom-modal :value="showModal" @cancel="onClose" effect="fade" width="60%">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title"> {{ title }} </h4>
            </div>
            <div slot="modal-body" class="modal-body">
                <div class="form-group">
                    <bs-input label="Group Name" type="text" required disabled  :maxlength="255" :icon="true" v-model="formData.name"></bs-input>
                    <bs-input label="Role Name" type="text" required disabled :maxlength="255" :icon="true" v-model="formData.role"></bs-input>
                    <div class="grp-group-panel">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <a>
                                    <strong>Resource Type | Resource | Permission</strong>
                                </a>
                                <span class="btn btn-del btn-primary btn-xs pull-right" title="New" @click="onClickNewGrp()">
                                                <span class="glyphicon glyphicon-plus"></span>
                                                NEW
                                </span>
                            </div>
                            <div class="list-grp-table">
                                <table class="table table-condensed">
                                    <thead>
                                    <tr>
                                        <th>Resource Type/Resource</th>
                                        <th>Permission </th>
                                        <th>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr is="grp-selection"
                                        v-for="(item, index) in group_resource_permissions"
                                        :key ="item"
                                        v-if="item"
                                        :selectedResourceType="item.resourceType"
                                        :selectedResource="item.resource"
                                        :selectedPermission="item.permission"
                                        :cascadeResourceOptions="cascadeResourceOptions"
                                        :permissionOptions="permissionOptions"
                                        :row-data="item"
                                        :row-index="index"
                                        @onDelete="onDeleteGrp"
                                    >
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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
    import GroupResourcePermissionSelection from './GroupResourcePermissionSelection.vue'

    export default {
        computed: {
            ...mapState({
                showModal: state => state.grp.showModal,
                grpData: state=> state.grp.grpData,
            }),
        },
        data () {
            return {
                title: '',
                formData: {
                    name: '',
                    role:'',
                    id: '',
                    group_resource_permissions: [],
                },
                group_resource_permissions: [],
                selectedRole: '',
                selectedGroup: '',
                cascadeResourceOptions: [],
                permissionOptions: [],
            }
        },
        created() {
            console.log('@@@@@@@@@@@@@@@@ GroupResourcePermissionCrudModal Component created.');
            // get resource type options
            this.$store.dispatch('getCascadeResourceOptions')
                .then((response) => {
                    console.log('created getCascadeResourceOptions success=', response);
                    this.cascadeResourceOptions = response.data.cascadeResources;
                })
                .catch((error) => {
                        console.error('getCascadeResourceOptions error=', error);
                });
            // at the same time get permission options
            this.$store.dispatch('getPermissionOptions')
                .then((response) => {
                    console.log('getPermissionOptions success=', response);
                    this.setPermissionOptions(response.data.permissions);
                })
                .catch((error) => {
                        console.error('getPermissionOptions error=', error);
                });
        },
        components: {
            'custom-modal': modal,
            'bs-input': input,
            'grp-selection': GroupResourcePermissionSelection,
        },
        mounted() {
            console.log('GroupResourcePermissionCrudModal Component mounted. grpData=', this.grpData)
        },
        methods: {
            setPermissionOptions(permissions) {
                console.log('&&&&&&&&&&&&&& Grp setPermissionOptions ');
                if (!permissions || permissions.length == 0) return;
                let options = [];
                for (let permission in permissions) {
                    options.push({value: permissions[permission].id, label: permissions[permission].name});
                }
                this.permissionOptions = options;
            },
            onDeleteGrp(data) {
                console.log('GroupResourcePermissionCrudModal onDeleteGrp', data);
                console.log('GroupResourcePermissionCrudModal onDeleteGrp data.index=', data.index);
                console.log('GroupResourcePermissionCrudModal this.group_resource_permissions', this.group_resource_permissions);
                this.group_resource_permissions.splice(data.index, 1);
            },
            onClickNewGrp() {
                console.log('onClickNewGrp');
                this.group_resource_permissions.push({resourceType: {id:'',name:''}, resource: {id:'',name:''}, permission: {id:'',name:''}});
                console.log('grpData ====== this.group_resource_permissions=', this.group_resource_permissions);
            },
            OnSave() {
                console.log('OnSave');
                let payload = {
                    isShow: false,
                    data: this.formData,
                };
                if (this.grpData.action === 'Add')
                {
                    // new state
                    this.$store.dispatch('setGrpShowModal', payload);
                    this.$store.dispatch('addGrpRequest', this.formData)
                        .then((response) => {
                            console.log('addGrpRequest fire events -> refreshGrpTable');
                            this.$events.fire('refreshGrpTable');
                        })
                        .catch((error) => {});
                }
                else if (this.grpData.action === 'Edit')
                {
                    // update
                    this.$store.dispatch('setGrpShowModal', payload);
                    this.$store.dispatch('updateGrpRequest', this.formData)
                        .then((response) => {
                            console.log('updateGrpRequest fire events -> refreshGrpTable');
                            this.$events.fire('refreshGrpTable');
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
                this.resetFormData();
                let payload = {
                    isShow: false,
                    data: this.formData,
                };
                this.formData.group_resource_permissions = [];
                this.group_resource_permissions = this.formData.group_resource_permissions;
                this.$store.dispatch('setGrpShowModal', payload);
            },
            resetFormData() {
                this.formData = {
                    name: '',
                    role: '',
                    id: '',
                    group_resource_permissions: [],
                };
            }
        },
        watch: {
            group_resource_permissions(newVal, oldVal)
            {
                console.log('watch group_resource_permissions ', newVal,oldVal)
            },
            grpData() {
                console.log('+++++++++++++ grpData changed =', this.grpData);
                this.selectedRole = this.grpData.selectedRole;
                if (this.grpData && this.grpData.action === 'Add')
                {
                    this.resetFormData();
                    this.title = 'Adding a new group resource permission';
                    this.formData.group_resource_permissions = [];
                    this.group_resource_permissions = this.formData.group_resource_permissions;
                }
                else if (this.grpData && this.grpData.action === 'Edit')
                {
                    this.resetFormData();
                    this.title = 'Editing the group resource permission';
                    this.formData.id = this.grpData.data.id;
                    this.formData.name = this.grpData.data.name;
                    this.formData.role = this.grpData.data.role.name;
                    let group_resource_permissions = this.grpData.data.group_resource_permissions;
                    let grpGroups = [];
                    for (let grp in group_resource_permissions) {
                        if (!group_resource_permissions[grp].resource_type || !group_resource_permissions[grp].permission || !group_resource_permissions[grp].resource) break;
                        console.log('******************* grp resource name= ', group_resource_permissions[grp].resource.name);
                        grpGroups.push({
                            resourceType: group_resource_permissions[grp].resource_type,
                            resource: group_resource_permissions[grp].resource,
                            permission: group_resource_permissions[grp].permission,
                            });
                    }
                    console.log('grpData ======  grpGroups=', grpGroups);
                    this.formData.group_resource_permissions = grpGroups;
                    this.group_resource_permissions = grpGroups;
                    console.log('grpData ====== this.formData.grpGroups=', this.formData.group_resource_permissions);
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
