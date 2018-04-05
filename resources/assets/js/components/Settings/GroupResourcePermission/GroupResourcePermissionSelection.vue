<template>
    <tr class="list-panel-row">
        <td width="60%">
            <div class="input-group-sm">
                <!--<v-select-->
                        <!--id="resourceTypeID"-->
                        <!--v-model="itemData.resourceType"-->
                        <!--:options="resourceTypeOptions"-->
                        <!--@change="onResourceTypeChanged">-->
                <!--</v-select>-->
                <Cascader v-model="cascade_resource_group"
                          :data="cascadeResourceOptions"
                          size="large"
                          :change-on-select="changeOnSelect"
                          placeholder="Please select a resource..."
                          @on-change="handleChange"
                          @on-item-change="onItemChanged"
                >
                </Cascader>
            </div>
        </td>
        <!--<td width="30%">-->
            <!--<div class="input-group-sm">-->
                <!--<v-select-->
                        <!--id="resourceID"-->
                        <!--v-model="itemData.resource"-->
                        <!--:options="resourceOptions"-->
                        <!--@change="onResourceChanged">-->
                <!--</v-select>-->
            <!--</div>-->
        <!--</td>-->
        <td width="30%">
            <div class="input-group-sm">
                <!--<v-select-->
                        <!--id="permissionID"-->
                        <!--v-model="itemData.permission"-->
                        <!--:options="permissionOptions"-->
                        <!--:value="defaultPermission"-->
                        <!--@change="onPermissionChanged">-->
                <!--</v-select>-->
                <Select labelInValue v-model="permission_id"
                        @on-change="onPermissionChanged"
                        size="large"
                        placeholder="Please select a permission..."
                >
                    <Option v-for="item in permissionOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                </Select>
            </div>
        </td>
        <td width="10%">
                <span class="btn btn-del btn-danger btn-sm" @click="onDelete(rowData, rowIndex)">
                    <span class="glyphicon glyphicon-trash"></span>
                </span>
        </td>
    </tr>
</template>

<script>
    import { mapState } from 'vuex'
    import select from 'vue-strap/src/Select'

    export default {
        components: {
            'v-select': select,
        },
        props: {
            selectedResourceType: {
                type: Object,
            },
            selectedResource: {
                type: Object,
            },
            selectedPermission: {
                type: Object,
            },
            rowData: {
                type: Object,
                required: true
            },
            rowIndex: {
                type: Number,
            },
            permissionOptions: {
                type: Array,
                default: [],
            },
            cascadeResourceOptions: {
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
                defaultResourceType: '',
                defaultResource: '',
                defaultPermission: '',
                resourceTypeOptions: [],
                resourceOptions: [],
                itemData: {resourceType: null ,resource: null, permission: null},
                cascade_resource_group: [],
                permission_id: '',
                changeOnSelect: true,
            }
        },
        created() {
            console.log('Grp created rowData=', this.rowData);
            console.log('Grp created rowData.resource=', this.rowData.resource);
            console.log('Grp created selectedResourceType=', this.selectedResourceType);
            console.log('Grp created selectedResource=', this.selectedResource);
            console.log('Grp created selectedPermission=', this.selectedPermission);
            this.cascade_resource_group = [];
            switch (this.selectedResourceType.id)
            {
                case 1:
                    // Location
                    this.cascade_resource_group.push( this.selectedResourceType.id);
                    if (parseInt(this.selectedResource.state_id))
                        this.cascade_resource_group.push(parseInt(this.selectedResource.state_id));
                    this.cascade_resource_group.push(this.selectedResource.id);
                    break;
                case 2:
                    // Menu
                    this.cascade_resource_group.push( this.selectedResourceType.id);
                    if (parseInt(this.selectedResource.parent_id))
                        this.cascade_resource_group.push(parseInt(this.selectedResource.parent_id));
                    this.cascade_resource_group.push(this.selectedResource.id);
                    break;
                case 3:
                    // Tab
                    this.cascade_resource_group.push( this.selectedResourceType.id);
                    if (parseInt(this.selectedResource.page_id))
                        this.cascade_resource_group.push(parseInt(this.selectedResource.page_id));
                    this.cascade_resource_group.push(this.selectedResource.id);
                    break;
                case 4:
                    // Page
                    this.cascade_resource_group.push( this.selectedResourceType.id);
                    this.cascade_resource_group.push(this.selectedResource.id);
                    break;
                case 5:
                    // Component
                    this.cascade_resource_group.push( this.selectedResourceType.id);
                    if (parseInt(this.selectedResource.page_id))
                        this.cascade_resource_group.push(parseInt(this.selectedResource.page_id));
                    if (parseInt(this.selectedResource.parent_id))
                        this.cascade_resource_group.push(parseInt(this.selectedResource.parent_id));
                    this.cascade_resource_group.push(this.selectedResource.id);
                    break;
                case 6:
                    // Process
                    this.cascade_resource_group.push( this.selectedResourceType.id);
                    if (this.selectedResource.component && this.selectedResource.component.page)
                        this.cascade_resource_group.push(this.selectedResource.component.page_id);
                    if (this.selectedResource.component && this.selectedResource.component.parent)
                        this.cascade_resource_group.push(this.selectedResource.component.parent.id);
                    this.cascade_resource_group.push(this.selectedResource.component.id);
                    this.cascade_resource_group.push(this.selectedResource.id);
                    break;
            }


            this.permission_id = this.selectedPermission.id;
            //this.itemData = this.rowData;
            // get all children roles based on selectedResourceType
//            this.$store.dispatch('getResourceTypeOptions')
//                .then((response) => {
//                    console.log('getResourceTypeOptions success=', response);
//                    this.setResourceTypeOptions(response.data.resourceTypes);
//                    this.$store.dispatch('getResourceOptions', this.defaultResourceType)
//                        .then((response) => {
//                            console.log('getResourceOptions success=', response);
//                            this.setResourceOptions(response.data.resources, this.defaultResourceType);
//                            if (!this.rowData.resourceType && !this.rowData.resource)
//                            {
//                                this.rowData.resourceType = this.defaultResourceType;
//                                this.rowData.resource = this.defaultResource;
//                                this.rowData.permission = this.defaultPermission;
//                            }
//                            this.itemData = this.rowData;
//                        })
//                        .catch((error) => {
//                                console.error('getResourceOptions error=', error);
//                        });
//                })
//                .catch((error) => {
//                    console.error('getResourceTypeOptions error=', error);
//                });
//            // at the same time get permission options
//            this.$store.dispatch('getPermissionOptions')
//                .then((response) => {
//                    console.log('getPermissionOptions success=', response);
//                    this.setPermissionOptions(response.data.permissions);
//                    //this.itemData = this.rowData;
//                })
//                .catch((error) => {
//                        console.error('getPermissionOptions error=', error);
//                });
        },
        watch: {
            itemData(newVal, oldVal) {
                console.log('&&&&&&&&&&&&&& watch itemData changed.', newVal, oldVal);
            }
        },
        methods: {
            onItemChanged() {
                console.log('onItemChanged');
            },
            handleChange (value, selectedData) {
                console.log('handleChange value=', value);
                console.log('handleChange selectedData=', selectedData);
                //this.formData.component.name = selectedData.map(o => o.label).join('/');
                //this.rowData.role
                this.rowData.resourceType = {id:'', name:''};
                this.rowData.resource = {id:'', name:''};
                if (selectedData.length > 0)
                {
                    this.rowData.resourceType = {id: selectedData[0].value, name: selectedData[0].label};
                    this.rowData.resource = {id: selectedData[selectedData.length-1].value, name: selectedData[selectedData.length-1].label};
                }
            },
//            setResourceTypeOptions(resourceTypes) {
//                console.log('&&&&&&&&&&&&&& Grp setResourceTypeOptions ');
//                let options = [];
//                for (let resourceType in resourceTypes) {
//                    options.push({value: resourceTypes[resourceType].name, label: resourceTypes[resourceType].name});
//                }
//                this.resourceTypeOptions = options;
//                if (options.length == 0) return;
//                this.defaultResourceType = this.selectedResourceType ? this.selectedResourceType : this.resourceTypeOptions[0].value;
//            },
//            setResourceOptions(resources, type) {
//                console.log('&&&&&&&&&&&&&& Grp setResourceOptions selectedResource= ',this.selectedResource);
//                if (!resources || resources.length == 0) return;
//                let options = [];
//                for (let res in resources) {
//                    let stateName = '';
//                    let locationName = '';
//                    let resourceName = '';
//                    let pageName = '';
//                    let parentName = '';
//                    let componentName = '';
//                    let tabName = '';
//                    let processName = '';
//                    let menuName = '';
//                    switch (type)
//                    {
//                        case 'Location':
//                            stateName = resources[res].state ? resources[res].state.name + '/' : '';
//                            locationName = stateName + resources[res].name;
//                            resourceName = locationName;
//                            break;
//                        case 'Component':
//                            pageName = resources[res].page ? resources[res].page.name + '/' : '';
//                            parentName = resources[res].parent ? resources[res].parent.name + '/' : '';
//                            componentName = pageName + parentName + resources[res].name;
//                            resourceName = componentName;
//                            break;
//                        case 'Tab':
//                            pageName = resources[res].page ? resources[res].page.name + '/' : '';
//                            tabName = pageName + resources[res].name;
//                            resourceName = tabName;
//                            break;
//                        case 'Process':
//                            if (!resources[res].component) continue;
//                            pageName = resources[res].component.page ? resources[res].component.page.name + '/' : '';
//                            parentName = resources[res].component.parent ? resources[res].component.parent.name + '/' : '';
//                            processName = pageName + parentName + resources[res].component.name + '/' + resources[res].name;
//                            resourceName = processName;
//                            break;
//                        case 'Menu':
//                            menuName = resources[res].name;
//                            resourceName = menuName;
//                            break;
//                        default:
//                            resourceName = resources[res].name;
//                            break;
//                    }
//                    options.push({value: resourceName, label: resourceName});
//                }
//                this.resourceOptions = options;
//                if (options.length == 0) return;
//                this.defaultResource = this.selectedResource ? this.selectedResource : this.resourceOptions[0].value;
//            },
//            setPermissionOptions(permissions) {
//                console.log('&&&&&&&&&&&&&& Grp setPermissionOptions ');
//                if (!permissions || permissions.length == 0) return;
//                let options = [];
//                for (let permission in permissions) {
//                    options.push({value: permissions[permission].name, label: permissions[permission].name});
//                }
//                this.permissionOptions = options;
//                if (options.length == 0) return;
//                this.defaultPermission = this.selectedPermission ? this.selectedPermission : this.permissionOptions[0].value;
//            },
            onDelete(rowData, rowIndex) {
                console.log('Grp onDelete');
                console.log('Grp onDelete rowData=', rowData);
                console.log('Grp onDelete =rowIndex', rowIndex);
                this.$emit('onDelete', {action: 'delete', data: rowData, index: rowIndex});

            },
//            onResourceTypeChanged(selVal) {
//                console.log('&&&&&&&&&&&&&& Grp onResourceTypeChanged selVal=', selVal, this.selectedResourceType);
//                //this.resourceOptions = [];
//                //this.defaultResource = '';
//                if (!selVal) return;
//                if (selVal == this.selectedResourceType) return;
//                // whenever role changes, get the associated groups
//                this.$store.dispatch('getResourceOptions', selVal)
//                    .then((response) => {
//                        console.log('getResourceOptions success=', response);
//                        this.resourceOptions = [];
//                        this.setResourceOptions(response.data.resources);
//                        this.rowData.resourceType = selVal;
//                        this.rowData.resource = this.resourceOptions.length > 0 ? this.resourceOptions[0].value : '';
//                    })
//                    .catch((error) => {
//                        console.error('getResourceOptions error=', error);
//                    });
//            },
//            onResourceChanged(selVal) {
//                console.log('&&&&&&&&&&&&&& Grp onResourceChanged selVal=', selVal);
//                if (!selVal) return;
//
//            },
            onPermissionChanged(selVal) {
                console.log('&&&&&&&&&&&&&& Grp onPermissionChanged selVal=', selVal);
                this.rowData.permission = {id: selVal.value, name: selVal.label};
            }
        }
    }
</script>
<style>
</style>