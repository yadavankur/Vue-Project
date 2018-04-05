
import VueRouter from 'vue-router';
import jwtToken from './helpers/jwt-token';
import Store from './store/index'
import * as api from './config';

import HomeView from './components/Home.vue';
import DashboardView from './components/Dashboard.vue';
//import RegisterView from './components/Register.vue';
import LoginView from './components/Login.vue';
import NotFoundView from './components/404.vue'
import NoPermissionView from './components/444.vue'
import NotAllowedView from './components/NotAllowed.vue'
import BrowserWarningView from './components/BrowserWarning.vue'

// password reset
import ResetPasswordView from './components/ResetPassword/ResetPassword.vue'

// dashboard main view
import DashboardMainView from './components/DashboardMain/DashBoardMain.vue'

// playground
import PlaygroundView from './components/Playground/playground.vue'

// system settings (security module)
import GroupListView from './components/Settings/Group/GroupList.vue'
import CpmServiceListView from './components/Settings/CriticalPath/Service/CpmServiceList.vue'
import CpmServiceGroupListView from './components/Settings/CriticalPath/ServiceGroup/CpmServiceGroupList.vue'
import CpmActivityListView from './components/Settings/CriticalPath/Activity/CpmActivityList.vue'
import CpmMasterListView from './components/Settings/CriticalPath/Master/CpmMasterList.vue'
import BusinessCalendarView from './components/Settings/BusinessCalendar/BusinessCalendarList.vue'
import RoleListView from './components/Settings/Role/RoleList.vue'
import UserListView from './components/Settings/User/UserList.vue'
import GroupResourcePermissionListView from './components/Settings/GroupResourcePermission/GroupResourcePermissionList.vue'
import NoteTypeView from './components/Settings/NoteType/NoteType.vue'
import NoteTypeActionView from './components/Settings/NoteTypeAction/NoteTypeAction.vue'
import TaskMappingView from './components/Settings/TaskMapping/TaskMapping.vue'
import DefaultSettingView from './components/Settings/DefaultSetting/DefaultSetting.vue'
import LogView from './components/Settings/Log/Log.vue'

// resource settings
import MenuListView from './components/Settings/Menu/MenuList.vue'
import StateListView from './components/Settings/State/StateList.vue'
import TabListView from './components/Settings/Tab/TabList.vue'
import Tab1ListView from './components/Newtab1/Tab1List.vue'
import PageListView from './components/Settings/Page/PageList.vue'
import LocationListView from './components/Settings/Location/LocationList.vue'
import ComponentListView from './components/Settings/Component/ComponentList.vue'
import ProcessListView from './components/Settings/Process/ProcessList.vue'
import PermissionListView from './components/Settings/Permission/PermissionList.vue'
import ResourceTypeListView from './components/Settings/ResourceType/ResourceTypeList.vue'

// responsive settings
import DeviceTypeListView from './components/Settings/DeviceType/DeviceTypeList.vue'
import ResponsiveSettingListView from './components/Settings/ResponsiveSetting/ResponsiveSettingList.vue'


// booking module vues
import OrderListView from './components/Booking/OrderList/OrderList.vue'
import OListView from './components/Ol2/OList.vue'
import OrderDetailsView from './components/Booking/OrderDetails/OrderDetails.vue'
import OrderItemsView from './components/Booking/OrderItems/OrderItems.vue'
import CustomerTierLevelView from './components/Booking/CustomerTierLevel/TierList.vue'
import CustomerTierView from './components/Booking/CustomerTier/CustomerTierList.vue'
import BookingView from './components/Booking/Booking/Booking.vue'
import ConfirmationView from './components/Booking/Confirmation/Confirmation.vue'
import CustomerServiceView from './components/Booking/CustomerService/CustomerService.vue'
import ExpediteConfirmationView from './components/Booking/ExpediteConfirmation/ExpediteConfirmation.vue'
import MyActivitiesView from './components/Booking/MyActivities/MyActivities.vue'
import NewView from './components/New1/NewView1.vue'
import NewView2 from './components/New2/New2View1.vue'
import Cpm1ActivityListView from './components/cpm1/Cpm1ActivityList.vue'
import Cpm2ServiceListView from './components/cpm2/Cpm2ServiceList.vue'
import Csticket from './components/cs/Cs1ticket.vue'
import Csttype from './components/cs/csttype/CsTicketType.vue'
import CsStatus from './components/cs/csstatus/CsTicketStatus.vue'

let routes = [
    { path: '/login', component: LoginView, name: 'login', meta: { requiresGuest: true } },
    { path: '/password/reset/:token', component: ResetPasswordView, name: 'resetpassword', meta: {requiresGuest: true} },
    { path: '/', component: HomeView, meta: { requiresAuth: true }, 
        children: [
            { path: '/playground', component: PlaygroundView,  meta: { requiresAuth: true }},
            { path: '/users', component: UserListView,  meta: { requiresAuth: true }},
            { path: '/roles', component: RoleListView,  meta: { requiresAuth: true }},
            { path: '/menus', component: MenuListView,  meta: { requiresAuth: true }},
            { path: '/tabs', component: TabListView,  meta: { requiresAuth: true }},
            { path: '/pages', component: PageListView,  meta: { requiresAuth: true }},
            { path: '/states', component: StateListView,  meta: { requiresAuth: true }},
            { path: '/locations', component: LocationListView,  meta: { requiresAuth: true }},
            { path: '/components', component: ComponentListView,  meta: { requiresAuth: true }},
            { path: '/processes', component: ProcessListView,  meta: { requiresAuth: true }},
            { path: '/permissions', component: PermissionListView,  meta: { requiresAuth: true }},
            { path: '/devicetypes', component: DeviceTypeListView,  meta: { requiresAuth: true }},
            { path: '/resourcetypes', component: ResourceTypeListView,  meta: { requiresAuth: true }},
            { path: '/responsivesettings', component: ResponsiveSettingListView,  meta: { requiresAuth: true }},
            { path: '/groupresourcepermissions', component: GroupResourcePermissionListView,  meta: { requiresAuth: true }},
            { path: '/groups', component: GroupListView,  meta: { requiresAuth: true }},
            { path: '/cpmservices', component: CpmServiceListView,  meta: { requiresAuth: true }},
            { path: '/cpmservicegroups', component: CpmServiceGroupListView,  meta: { requiresAuth: true }},
            { path: '/cpmactivities', component: CpmActivityListView,  meta: { requiresAuth: true }},
            { path: '/cpmmasters', component: CpmMasterListView,  meta: { requiresAuth: true }},
            { path: '/customertierlevels', component: CustomerTierLevelView,  meta: { requiresAuth: true }},
            { path: '/customertiers', component: CustomerTierView,  meta: { requiresAuth: true }},
            { path: '/businesscalendar', component: BusinessCalendarView,  meta: { requiresAuth: true }},
            { path: '/notetypes', component: NoteTypeView,  meta: { requiresAuth: true }},
            { path: '/notetypeactions', component: NoteTypeActionView,  meta: { requiresAuth: true }},
            { path: '/taskmappings', component: TaskMappingView,  meta: { requiresAuth: true }},
            { path: '/defaultsettings', component: DefaultSettingView,  meta: { requiresAuth: true }},
            { path: '/logs', component: LogView,  meta: { requiresAuth: true }},
            { path: '', component: DashboardView, name: 'dashboard', meta: { requiresAuth: true },
                // all tabs are put here
                children: [
                    { path: '/dashboard', name: 'dashbaordmain', component: DashboardMainView, meta: { requiresAuth: true, keepAlive: false } },
                    { path: '/orderlist', name: 'orderlist', component: OrderListView, meta: { requiresAuth: true, keepAlive: true } },
                    { path: '/orderdetails', name: 'orderdetails', component: OrderDetailsView, meta: { requiresAuth: true, keepAlive: false } },
                    { path: '/items', name: 'items', component: OrderItemsView, meta: { requiresAuth: true, keepAlive: false } },
                    { path: '/booking', name: 'booking', component: BookingView, meta: { requiresAuth: true, keepAlive: false }},
                    { path: '/confirmation', name: 'confirmation', component: ConfirmationView, meta: { requiresAuth: true, keepAlive: false }},
                    { path: '/customerservice', name: 'customerservice', component: CustomerServiceView, meta: { requiresAuth: true, keepAlive: false }},
                    { path: '/expediteconfirmation', name: 'expediteconfirmation', component: ExpediteConfirmationView, meta: { requiresAuth: true, keepAlive: false }},
                    { path: '/myactivities', name: 'myactivities', component: MyActivitiesView, meta: { requiresAuth: true, keepAlive: false }},
                    { path: '/new', name: 'New', component: NewView, meta: { requiresAuth: true, keepAlive: false }},
                    { path: '/manoj', name: 'Manoj', component: NewView2, meta: { requiresAuth: true, keepAlive: false }},
                    { path: '/customerservice1', name: 'customerservice1', component: CustomerServiceView, meta: { requiresAuth: true, keepAlive: false }},
                    { path: '/ol', name: 'ol', component: OListView, meta: { requiresAuth: true, keepAlive: false }},
                    { path: '/od', name: 'od', component: OrderDetailsView, meta: { requiresAuth: true, keepAlive: false }},
                    { path: '/book', name: 'book', component: BookingView, meta: { requiresAuth: true, keepAlive: false }},
                    { path: '/tab1', component: Tab1ListView,  meta: { requiresAuth: true }},
                    { path: '/cpm1', component: Cpm1ActivityListView,  meta: { requiresAuth: true }},
                    { path: '/cpm2', component: Cpm2ServiceListView,  meta: { requiresAuth: true }},
                    { path: '/cs', component: Csticket,  meta: { requiresAuth: true }},
                    { path: '/csttype', component: Csttype,  meta: { requiresAuth: true }},
                    { path: '/cststatus', component: CsStatus,  meta: { requiresAuth: true }},
                    
                ]
            },
        ]
    },
    { path: '/nopermission', component: NoPermissionView, name: 'nopermission', meta: {} },
    { path: '/notallowed', component: NotAllowedView, name: 'notallowed', meta: {} },
    { path: '/browserwarning', component: BrowserWarningView, name: 'browserwarning', meta: {} },
    { path: '*', component: NotFoundView, meta: {} },  
];

const router = new VueRouter({
    mode: 'history',    base: __dirname,    routes: routes,// linkActiveClass: 'active'
    scrollBehavior: function (to, from, savedPosition) { return savedPosition || { x: 0, y: 0 }  }
});

router.beforeEach((to, from, next) => {
    console.log('/resources/js/router.js-router.beforeEach Store.state.authUser.authenticated+++++++=', Store.state.authUser.authenticated);
    console.log('/resources/js/router.js-router.beforeEach from+++++++=', from);
    console.log('/resources/js/router.js-router.beforeEach to+++++++=', to);
    Store.dispatch('hideAllNotifications');
    
    if(to.meta.requiresAuth) 
    {   if(Store.state.authUser.authenticated || jwtToken.getToken())
        {   if ((to.path == '/') || (to.path ==  api.homeUrlPath))
            {   return next();   }
            else
            {
                console.log('/resources/js/router.js+++++++++ router.beforeEach checkPermission start');
                // check accessible or not
                Store.dispatch('checkPermission', {urlPath: to.path})
                    .then((response) => {
                        console.log('/resources/js/router.js-++++++router.beforeEach -> checkPermission response=', response);
                        return next();
                    })
                    .catch((error) => {
                        console.log('/resources/js/router.js-++++++checkPermission error=', error);
                        return next({name: 'nopermission'});
                    });
                console.log('/resources/js/router.js+++++++++++ router.beforeEach checkPermission end');
            }
        }
        else
        {   console.log('/resources/js/router.js-+++++Store.state', Store.state);
            return next({name: 'login'});
        }
    }
    console.log('/resources/js/router.js+++++++++ router.beforeEach +++++++++++++ middle');
    if(to.meta.requiresGuest) {
        console.log('/resources/js/router.js+++++++++ router.beforeEach ++++++++++++ requiresGuest');
        if(Store.state.authUser.authenticated || jwtToken.getToken())
            return next({name: 'dashboard'});
        else
            return next();
    }
    console.log('/resources/js/router.js+++++++ router.beforeEach +++++++++++++++ end');
    next();
});

router.afterEach(() => {    console.log('/resources/js/router.js-+++++router.afterEach');});

export default router;