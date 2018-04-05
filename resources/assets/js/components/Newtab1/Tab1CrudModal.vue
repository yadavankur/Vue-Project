<template>
    <div>
        <custom-modal :value="showModal" @cancel="onClose" effect="fade">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title"> {{ title }} </h4>
            </div>
            <div slot="modal-body" class="modal-body">
                <div class="form-group">
                    <bs-input label="Tab Name" type="text" required  :maxlength="255" :icon="true" v-model="formData.name"></bs-input>
                    <bs-input label="Url Path" type="text" required  :maxlength="255" :icon="true" v-model="formData.link"></bs-input>
                    <div class="form-group">
                        <div><label for="Page">Page</label></div>
                        <Select clearable filterable labelInValue v-model="formData.page.id"
                                @on-change="OnChanged"   size="large"   placeholder="Select a page or leave blank..." >
                            <Option v-for="item in pageOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                        </Select>
                    </div>
                    <Checkbox v-model="formData.isGShow">Always Shown?</Checkbox>
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
    import select from 'vue-strap/src/Select'

    export default 
    {  computed: {  ...mapState({   showModal: state => state.tab.showModal, tabData: state=> state.tab.tabData,}),  },
       props: { selectedPage: '', },
       data () 
        {   return {  title: '',
                      formData: {  isGShow: false, name: '', link: '', page: {name:'',id: ''}, comment: '', id: ''  },
                      defaultPage: '', pageOptions: [], minSearch: 1,
                   }
        },
        created() 
        {   console.log('/components/settings/tab/TabCrudModal.vue--CustomModal Component created.')
            this.$store.dispatch('getPageOptions')  // get page list
                .then((response) => 
                {   console.log('/components/settings/tab/TabCrudModal.vue--created getPageOptions success=', response);
                    this.setPageOptions(response.data.pages);
                })
                .catch((error) => { console.error('/components/settings/tab/TabCrudModal.vue---getPageOptions error=', error); });
        },
        components: { 'custom-modal': modal, 'bs-input': input,'v-select': select, },
        mounted() { console.log('/components/settings/tab/TabCrudModal.vue--CustomModal Component mounted. tabData=', this.tabData)  },
        methods: 
        {   OnChanged(selVal) {   console.log('/components/settings/tab/TabCrudModal.vue---OnChanged selVal=', selVal);
                                  if (!selVal) return;
                                  this.formData.page.name = selVal.label;
                               },
            setPageOptions(pages) {  console.log('/components/settings/tab/TabCrudModal.vue---setPageOptions pages=',pages);
                                     let options = [];
                                     for (let page in pages) { options.push({value: pages[page].id, label: pages[page].name}); }
                                     this.pageOptions = options;
                                     if (options.length == 0) return;
                                     this.defaultPage = this.selectedPage? this.selectedPage: this.pageOptions[0].value;
                                   },
            OnSave() {    console.log('/components/settings/tab/TabCrudModal.vue---OnSave');
                          let payload = { isShow: false,  data: this.formData, };
                          if (this.tabData.action === 'Add')// new tab
                             {  this.$store.dispatch('setTabShowModal', payload);
                                this.$store.dispatch('addTabRequest', this.formData)
                                .then((response) => {
                                   console.log('/components/settings/tab/TabCrudModal.vue--addTabRequest fire events -> refreshTabTable');
                                   this.$events.fire('refreshTabTable');
                                   })
                                .catch((error) => {});
                             }
                           else if (this.tabData.action === 'Edit') // update
                            {  this.$store.dispatch('setTabShowModal', payload);
                               this.$store.dispatch('updateTabRequest', this.formData)
                               .then((response) => 
                               {   console.log('/components/settings/tab/TabCrudModal.vue---updateTabRequest fire events -> refreshTabTable');
                                   this.$events.fire('refreshTabTable');
                                })
                              .catch((error) => {});
                           }
                          else   {    } // error
                     },
            onClose() {  console.log('/components/settings/tab/TabCrudModal.vue---onClose');
                         let payload = { isShow: false, data: this.formData,  };
                                         this.$store.dispatch('setTabShowModal', payload);
                                         this.resetFormData();
                      },
            resetFormData() { this.formData = {  name: '', isGShow: false, link: '', page: {name:'',id: ''}, comment: '', id: ''  };
                            }
        },
        watch: 
        { tabData() {  console.log('/components/settings/tab/TabCrudModal.vue---+++++++++++++ tabData changed =', this.tabData);
                       if (this.tabData && this.tabData.action === 'Add')
                          {  this.resetFormData();  //this.formData.parent_id = this.tabData.data.id;  //this.formData.parent.name = this.tabData.data.name;
                             this.title = 'Adding a new tab';
                          }
                       else if (this.tabData && this.tabData.action === 'Edit')
                          {   this.resetFormData();   this.title = 'Editing the tab';
                              this.formData.id = this.tabData.data.id;  this.formData.link = this.tabData.data.link;
                              this.formData.name = this.tabData.data.name;  this.formData.isGShow = this.tabData.data.isGShow;
                              this.formData.comment = this.tabData.data.comment;
                              this.formData.page.name = this.tabData.data.page? this.tabData.data.page.name : '';
                              this.formData.page.id = this.tabData.data.page? this.tabData.data.page.id : '';
                          }
                    }
        }
    }
</script>

<style scoped>
</style>
