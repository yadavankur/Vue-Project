require('es6-promise').polyfill();

import Vue from 'vue';
import Vuex from 'vuex';

import notification from "./modules/notification";
import authUser from "./modules/auth-user";
import authApi from '../helpers/auth-api';
import login from "./modules/login";
import menu from "./modules/menu";
import tab from "./modules/tab";
import tab1new from "./modules/tab1new";
import role from "./modules/role";
import loading from "./modules/loading";
//import state from "./modules/state";
import new1 from "./modules/new1";
import new2 from "./modules/new2";
import user from "./modules/user";
import grp from './modules/group-resource-permission';
import page from './modules/page';
import location from './modules/location';
import component from './modules/component';
import process from './modules/process';
import permission from './modules/permission';
import deviceType from './modules/device-type';
import resourceType from './modules/resource-type';
import responsiveSetting from './modules/responsive_setting';
import group from './modules/group';
import cpmService from './modules/cpm-service';
import cpm2 from './modules/cpm2';
import cpm1 from './modules/cpm1';
import cpmActivity from './modules/cpm-activity';
import cpmMaster from './modules/cpm-master';
import cpmServiceGroup from './modules/cpm-service-group'

import customerTierLevel from "./modules/customer-tier-level";
import customerTier from "./modules/customer-tier";

import orderDetails from "./modules/order-details";
import cashSaleDetails from  "./modules/cash-sale-details";
import creditInfo from  "./modules/credit-info";

import orderItems from "./modules/order-items";
import booking from "./modules/booking";
import confirmation from "./modules/confirmation";
import customerService from "./modules/customer-service"
import dashboard from "./modules/dashboard";
import businessCalendar from "./modules/business-calendar"
import noteType from "./modules/note-type"
import taskMapping from "./modules/task-mapping"
import defaultSetting from "./modules/default-setting"

import csticket from "./modules/csticket"
import csticketstatus from "./modules/csticket-status"
import cstickettype from "./modules/csticket-type"
import cstkt from "./modules/cs-ticket"
import csticketcomments from "./modules/cstickets-comments"

import csticketcnstatus from "./modules/csticket-cnstatus"

import csticketerror from "./modules/csticket-error"


Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        authApi,
        loading,
        notification,
        authUser,
        login,
        tab,
        tab1new,
        role,
        new1,
       // state,
        cstkt,
        csticket,
        csticketstatus, csticketcnstatus,csticketerror,
        cstickettype,
        csticketcomments,
        new2,
        user,
        grp,
        page,
        location,
        component,
        process,
        permission,
        deviceType,
        resourceType,
        responsiveSetting,
        menu,
        group,
        cpmService,
        cpm2,
        cpm1,
        cpmServiceGroup,
        cpmActivity,
        cpmMaster,
        orderDetails,
        cashSaleDetails,
        creditInfo,
        customerTierLevel,
        customerTier,
        orderItems,
        booking,
        confirmation,
        customerService,
        dashboard,
        businessCalendar,
        noteType,
        taskMapping,
        defaultSetting,
    },
    strict: true
});