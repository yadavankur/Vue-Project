<template>
    <div v-if="isShow" class="ibox ibox-primary float-e-margins">
        <div class="ibox-title">
            <slot name="ibox-title">
                <h5>{{title}}</h5>
            </slot>
            <div class="ibox-tools">
                <a class="collapse-link" @click="onClickCollapse()">
                    <i :class="[ isShowContent ? 'fa fa-chevron-down' : 'fa fa-chevron-right' ]"></i>
                </a>
                <a v-if="closeable" class="close-link" @click="onClickCloseLink()">
                    <i class="fa fa-times"></i>
                </a>
            </div>
        </div>
        <div v-if="subtitle" class="ibox-content ibox-heading" v-show="isShowContent">
            <slot name="ibox-content-heading" >
                <h3><i class="fa fa-envelope-o"></i> {{ subtitle }}</h3>
                <small><i class="fa fa-tim"></i>{{ subtitleMessage }}</small>
            </slot>
        </div>
        <div v-show="isShowContent">
            <slot name="ibox-content">
            </slot>
        </div>
    </div>
</template>

<script>
    export default {
        props:
        {
            closeable: {type: Boolean, default: true},
            title: {type: String, default: null},
            subtitle: {type: String, default: null},
            subtitleMessage: {type: String, default: null},
        },
        data () {
            return {
                isShow: true,
                isShowContent: true,
            }
        },
        created() {
            console.log('/resources/assets/js/components/IBox/IBOX.vue- ibox Component created.')
        },
        components: {
        },
        mounted() {
            console.log('/resources/assets/js/components/IBox/IBOX.vue-ibox Component mounted.')
        },
        methods: {
            onClickCloseLink() {
                console.log('/resources/assets/js/components/IBox/IBOX.vue-ibox onClickCloseLink.')
                this.isShow = false;
            },
            onClickCollapse() {
                console.log('/resources/assets/js/components/IBox/IBOX.vue-ibox onClickCollapse.')
                this.isShowContent = !this.isShowContent;
            }
        },
        directives: {

        },
        filters: {

        },
        mixins: {

        }
    }
</script>

<style scoped>
    .ibox {
        clear: both;
        margin-bottom: 25px;
        margin-top: 0;
        padding: 0;
        border-style: solid;
        border: 1px solid transparent;
        border-radius: 4px;
    }
    .ibox-primary {
        border-color: #3097D1;
    }
    .collapse-link > i {
        color: white;
    }
    .close-link > i {
        color: white;
    }
    .ibox.collapsed .ibox-content {
        display: none;
    }
    .ibox.collapsed .fa.fa-chevron-down:before {
        content: "\f078";
    }
    .ibox.collapsed .fa.fa-chevron-right:before {
        content: "\f077";
    }
    .ibox:after,
    .ibox:before {
        display: table;
    }
    .ibox-title {
        -moz-border-bottom-colors: none;
        -moz-border-left-colors: none;
        -moz-border-right-colors: none;
        -moz-border-top-colors: none;
        color: white;
        background-color: #0a5b9e;
        border-color: #0a5b9e;
        border-image: none;
        border-style: solid solid none;
        border-width: 2px 0 0;
        margin-bottom: 0;
        padding: 15px 15px 7px;
        min-height: 40px;
        border-top-right-radius: 3px;
        border-top-left-radius: 3px;
    }
    .ibox-content {
        background-color: #ffffff;
        color: inherit;
        /*padding: 15px 20px 20px 20px;*/
        border-color: #e7eaec;
        border-image: none;
        border-style: solid solid none;
        border-width: 1px 0;
        margin-right: 1px;
        margin-bottom: 1px;
        margin-left: 1px;
    }
    .ibox-footer {
        color: inherit;
        border-top: 1px solid #e7eaec;
        font-size: 90%;
        background: #ffffff;
        padding: 10px 15px;
    }
    .ibox-content {
        clear: both;
    }
    .ibox-heading {
        background-color: #f3f6fb;
        border-bottom: none;
    }
    .ibox-heading h3 {
        font-weight: 200;
        font-size: 24px;
    }
    .title-link {
        color: #fff;
        cursor: pointer;
    }
    .ibox-title h5 {
        display: inline-block;
        font-size: 12px;
        margin: 0 0 7px;
        padding: 0;
        text-overflow: ellipsis;
        float: left;
    }
    .ibox-title .label {
        float: left;
        margin-left: 4px;
    }
    .ibox-tools {
        display: block;
        float: none;
        margin-top: 0;
        position: relative;
        padding: 0;
        text-align: right;
    }
    .ibox-tools a {
        cursor: pointer;
        margin-left: 5px;
        color: #c4c4c4;
    }
    .ibox-tools a.btn-primary {
        color: #fff;
    }
    .ibox-tools .dropdown-menu > li > a {
        padding: 4px 10px;
        font-size: 12px;
    }
    .ibox .ibox-tools.open > .dropdown-menu {
        left: auto;
        right: 0;
    }
</style>
