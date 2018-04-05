<template>
    <div>
        <custom-modal :value="showModal" @cancel="onClose" effect="fade">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title"> {{ title }} </h4>
            </div>
            <div slot="modal-body" class="modal-body" v-if="showModal">
                <div class="form-group">
                    <div class="form-group">
                        <div><label for="service">Service</label></div>
                        <Cascader v-model="cascade_location_service"
                                  :data="cascadeServiceOptions"
                                  size="large"
                                  :disabled="formData.id != ''"
                                  placeholder="Please select a service..."
                                  @on-change="handleChange"
                        >
                        </Cascader>
                    </div>
                    <div class="form-group">
                        <div><label for="group">Service Group</label></div>
                        <Select clearable
                                filterable
                                :value="service_group"
                                @on-change="onChangeServiceGroup"
                                placeholder="Please select a service group..."
                        >
                            <Option v-for="item in serviceGroupOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                        </Select>
                    </div>
                    <bs-input label="Activity Name" type="text" required  :maxlength="255" :icon="true" v-model="formData.name"></bs-input>
                    <bs-input label="Activity Duration" type="text" required  :maxlength="10" :icon="true" v-model="formData.duration"></bs-input>
                    <Checkbox v-model="formData.isFull">Need Full Day?</Checkbox>
                    <Checkbox v-model="formData.tick_option">Need Manual Confirm?</Checkbox>
                    <!--<bs-input label="SQL Statement" type="textarea" :maxlength="10240" v-model="formData.sql_statement"></bs-input>-->
                    <div class="form-group">
                        <div><label for="sqlStatement">SQL Statement1</label></div>
                        <Input type="textarea"
                               :autosize="{minRows: 2,maxRows: 5}"
                               :maxlength="10240"
                               v-model="formData.sql_statement"></Input>
                    </div>
                    <div class="form-group">
                        <div><label for="actionSql">Action SQL</label></div>
                        <Input type="textarea"
                               :autosize="{minRows: 2,maxRows: 5}"
                               :maxlength="10240"
                               v-model="formData.action_sql"></Input>
                    </div>
                    <div class="form-group">
                        <div><label for="comment">Comment</label></div>
                        <Input type="textarea"
                               :autosize="{minRows: 2,maxRows: 5}"
                               :maxlength="255"
                               v-model="formData.comment"></Input>
                    </div>
                    <!--<bs-input label="Comment" type="textarea" :maxlength="255" :icon="true" v-model="formData.comment"></bs-input>-->
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

    export default 
    {   computed: {   ...mapState({  showModal: state => state.cpm1.showModal,
                                    cpm1ActivityData: state=> state.cpm1.cpm1ActivityData,
               //so now cpm1Activity of this file(defined below)= value from store cpm1 file
                                  }),
                   },
        props: {        },
        data () {  return {   title: '',
                             formData: {  name: '',  duration: '', isFull: true, tick_option: false,
                                         sql_statement: '',
                    action_sql: '',     comment: '',    id: '',   service_id: '',
                    service: {name:'', id:''},   service_group: '', state: {name:'', id:''},  location: {name:'', id:''},
                },
                cascadeServiceOptions: [],
                cascade_location_service: [],
                serviceGroupOptions: [],
                service_group: '',
            }
        },
        created() 
        {   console.log('/cpm1/Crud.vue--CustomModal Component created.');
            this.getCascadeLocationServices();
        },
        components: 
        {     'custom-modal': modal,     'bs-input': input,   },
                mounted() { console.log('/cpm1/Crud.vue--cpmActivityData=', this.cpmActivityData)    },
                beforeDestroy() 
                    {
                     console.log('/cpm1/Crud.vue-- beforeDestroy!');
                     let payload = {   isShow: false,   data: { data: null},      };
                     this.$store.dispatch('setCpmActivityShowModal', payload);
                    },
            methods: {    onChangeServiceGroup(value) 
                            {  console.log('/cpm1/Crud.vue--onChangeServiceGroup value=', value);
                                 this.formData.service_group = value;
                            },
                         getCpmServiceGroupOptions(payload, service_group) 
                           {    this.$store.dispatch('getCpmServiceGroupOptions', payload)
                                           .then((response) =>
                                     { console.log('/cpm1/Crud.vue-- getCpmServiceGroupOptions success=', response);
                                       this.setServiceGroupOptions(response.data.serviceGroups);
                                      if (service_group)  this.service_group = service_group;
                                    })
                                    .catch((error) => { console.error('/cpm1/Crud.vue-- getCpmServiceGroupOptions error=', error);  });
                            },
                        setServiceGroupOptions(groups) 
                             {  console.log('/cpm1/Crud.vue--setServiceGroupOptions groups=',groups);
                                let options = [];
                                for (let group in groups) {   options.push({value: parseInt(groups[group].value), label: groups[group].label});  }
                                this.serviceGroupOptions = options;
                            },
                         handleChange (value, selectedData) 
                           {
                                console.log('handleChange value=', value);
                                console.log('handleChange selectedData=', selectedData);
                                this.formData.state = {id:'', name:''};
                                this.formData.location = {id:'', name:''};
                                this.formData.service = {id:'', name:''};
                                 this.serviceGroupOptions = [];
                                 if (selectedData.length > 0)
                                     {
                                     //this.formData.state = {id: selectedData[0].value, name: selectedData[0].label};
                                      this.formData.location = {id: selectedData[0].value, name: selectedData[0].label};
                                      this.formData.service = {id: selectedData[selectedData.length-1].value, name: selectedData[selectedData.length-1].label};
                                      let payload = {
                                      serviceId: this.formData.service.id,
                                      };
                                     this.getCpmServiceGroupOptions(payload);
                                    }
                            },
                       OnChanged(selVal) {   console.log('/cpm1/Crud.vue--OnChanged selVal=', selVal);    },
                      OnSave() 
                      {    console.log('/cpm1/Crud.vue--OnSave');
                           let payload = {   isShow: false,  data: { data: null},    };
                           if (this.cpmActivityData.action === 'Add')
                            {  this.$store.dispatch('addCpmActivityRequest', this.formData)
                               .then((response) => 
                               {   this.$store.dispatch('setCpmActivityShowModal', payload);
                                   console.log('/cpm1/Crud.vue--addCpmActivityRequest fire events -> refreshCpmActivityTable');
                                   this.$events.fire('refreshCpmActivityTable');
                               })
                              .catch((error) => {});
                            }
                            else if (this.cpmActivityData.action === 'Edit')
                            {    // update
                            this.$store.dispatch('updateCpmActivityRequest', this.formData)
                            .then((response) => 
                                {  this.$store.dispatch('setCpmActivityShowModal', payload);
                                 console.log('/cpm1/Crud.vue--updateCpmActivityRequest fire events -> refreshCpmActivityTable');
                                  this.$events.fire('refreshCpmActivityTable');
                                })
                           .catch((error) => {});
                           }
                      },
            onClose() 
            {  console.log('/cpm1/crud---onClose');  //-----when add popup comes---once u leave it or close it
                //it should again tell the value to store
                let payload = {  isShow: false,  data: { data: null},  };
                this.$store.dispatch('setCpm1ActivityShowModal', payload);
                this.resetFormData();
            },
            resetFormData() 
              { this.formData = 
                  { name: '', duration: '', isFull: true, tick_option: false, sql_statement: '',
                    action_sql: '',  comment: '',
                    id: '',  service_id: '', state: {name:'', id:''},
                    location: {name:'', id:''}, service: {name:'', id:''}, service_group: '',
                };
                this.cascade_location_service = [];
                this.serviceGroupOptions = [];
                this.service_group = '';
            }
        },
        watch: 
        {  cpm1ActivityData(newVal, oldVal) 
            {   console.log('/cpm1/Crud.vue--+++++++++++++ cpmActivityData changed newVal=', newVal);
                console.log('/cpm1/Crud.vue--+++++++++++++ cpmActivityData changed oldVal=', oldVal);
                console.log('/cpm1/Crud.vue--+++++++++++++ cpmActivityData changed =', this.cpm1ActivityData);
                if (this.cpm1ActivityData && this.cpm1ActivityData.action === 'Add')
                {  this.resetFormData();  this.title = 'Adding a new cpm1 activity';  }
                else if (this.cpm1ActivityData && this.cpm1ActivityData.action === 'Edit')
                {
                    this.resetFormData();
                    this.title = 'Editing the cpm1 activity';
                    this.formData.id = this.cpmActivityData.data.id;
                    this.formData.name = this.cpmActivityData.data.name;
                    this.formData.duration = this.cpmActivityData.data.duration;
                    this.formData.isFull = this.cpmActivityData.data.is_full;
                    this.formData.sql_statement = this.cpmActivityData.data.sql_statement;
                    this.formData.action_sql = this.cpmActivityData.data.action_sql;
                    this.formData.tick_option = this.cpmActivityData.data.tick_option;
                    this.formData.comment = this.cpmActivityData.data.comment;
                    this.formData.service_id = this.cpmActivityData.data.service.id;
                    this.formData.service = {  id : this.cpmActivityData.data.service.id,
                                               name: this.cpmActivityData.data.service.name
                                            };
                    this.formData.location = { id : this.cpmActivityData.data.service.location.id,
                                                name: this.cpmActivityData.data.service.location.name
                                            };
                    this.cascade_location_service.push(this.formData.location.id);
                    this.cascade_location_service.push(this.formData.service.id);
                    //console.log('&&&&&&&&& service_group =', parseInt(this.cpmActivityData.data.service_group_activity.service_group_id));
                    // must be here or will trriger onChange Event if put before getCpmServiceGroupOptions
                    let service_group = this.cpmActivityData.data.service_group_activity? parseInt(this.cpmActivityData.data.service_group_activity.service_group_id) : '';

                    let payload = { serviceId: this.formData.service.id, };
                    this.service_group = '';
                    this.getCpmServiceGroupOptions(payload, service_group);
                    this.formData.service_group = service_group;
                }
            }
        }
    }
</script>
<style scoped>
</style>
