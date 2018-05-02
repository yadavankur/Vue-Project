<?php

Route::post('authenticate', 'Auth\AuthController@authenticate');
Route::post('retrievepassword', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::post('resetpassword', 'Auth\ResetPasswordController@reset');

Route::group(['middleware' => 'jwt.auth'], function()
{
    Route::post('runcode', function() {
        // run customer php code
        // only for test for Mark
        // TODO
        // need to remove this route
        // when officially releasing
        $code = request()->code;
        ob_start();
        eval($code);
        $result = ob_get_contents();
        ob_end_clean();
        return $result;
    });
    // dashboard
    Route::get('notificationlist', 'MessageController@getAllMessages');
    Route::get('tasklist', 'CpmOrderController@getAllTasks');
    Route::post('updatenotificationstatus', 'MessageController@updateNotificationStatus');
    Route::get('getbadgecount', 'MessageController@getBadgeCount');
    Route::get('taskoverviewbarchart', 'CpmOrderController@getTaskOverviewBarChart');
    Route::get('taskoverviewdoughnutchart', 'CpmOrderController@getTaskOverviewDoughnutChart');

    // business calendar
    Route::get('businesscalendarlist', 'BusinessCalendarController@getBusinessCalendarList');
    Route::get('businesscalendartypes', 'BusinessCalendarController@getBusinessCalendarTypes');
    Route::get('businesscalendarfilteroptions', 'BusinessCalendarController@getBusinessCalendarFilterOptions');
    Route::post('savebusinesscalendar', 'BusinessCalendarController@saveBusinessCalendar');
    Route::post('updatebusinesscalendar', 'BusinessCalendarController@updateBusinessCalendar');
    Route::post('deletebusinesscalendar', 'BusinessCalendarController@deleteBusinessCalendar');

    Route::get('checkpermission', 'PermissionController@checkPermission');

    Route::get('user', 'UserController@show');
    Route::post('user/profile/update', 'UserController@updateProfile');
    Route::post('user/password/update', 'UserController@updatePassword');
//tab
    Route::get('tabs', 'TabController@show');
    Route::get('tabnodes', 'TabController@getTabNodes');
    Route::post('tab/edit/', 'TabController@updateTab');
    Route::post('tab/add/', 'TabController@addTab');
    Route::post('tab/delete/', 'TabController@deleteTab');
//tab1
    Route::get('tab1nodes', 'Tab1Controller@getTab1Nodes');


    Route::get('pagenodes', 'PageController@getPageNodes');
    Route::post('page/edit/', 'PageController@updatePage');
    Route::post('page/add/', 'PageController@addPage');
    Route::post('page/delete/', 'PageController@deletePage');
    Route::get('pageoptions', 'PageController@getPageOptions');

    Route::get('roles', 'RoleController@lists');
    Route::get('rolenodes', 'RoleController@getRoleNodes');
    Route::post('role/edit/', 'RoleController@updateRole');
    Route::post('role/add/', 'RoleController@addRole');
    Route::post('role/delete/', 'RoleController@deleteRole');
    Route::get('role/allroleoptions/', 'RoleController@getAllRoleOptions');


    Route::get('menus', 'MenuController@show');
    //Route::get('menunodes', 'MenuController@getMenuNodes');
    Route::get('menunodes', 'MenuController@getPagination');
    Route::post('menu/edit/', 'MenuController@updateMenu');
    Route::post('menu/add/', 'MenuController@addMenu');
    Route::post('menu/delete/', 'MenuController@deleteMenu');

    Route::get('statenodes', 'StateController@getStateNodes');
    Route::post('state/edit/', 'StateController@updateState');
    Route::post('state/add/', 'StateController@addState');
    Route::post('state/delete/', 'StateController@deleteState');
    Route::get('stateoptions', 'StateController@getStateOptions');

   // Route::get('statenodesnew2', 'Testb1Controller@getStateNodesnew2');
    Route::get('statenodesnew2', 'Testb1Controller@getTest2');
    Route::post('test/add/', 'Testb1Controller@addTest');
    Route::post('test/delete/', 'Testb1Controller@deleteTest');
    Route::post('test/edit/', 'Testb1Controller@updateTest');

    Route::get('locationnodes', 'LocationController@getLocationNodes');
    Route::post('location/edit/', 'LocationController@updateLocation');
    Route::post('location/add/', 'LocationController@addLocation');
    Route::post('location/delete/', 'LocationController@deleteLocation');
    Route::get('locationoptions', 'LocationController@getLocationOptions');
    Route::get('assignedlocationoptions', 'LocationController@getAssignedLocationOptions');
    Route::get('cascadelocationoptions', 'StateController@getCascadeLocations');
    Route::get('assignedcascadelocationoptions', 'StateController@getAssignedCascadeLocations');

    Route::get('components', 'ComponentController@show');
    Route::get('componentnodes', 'ComponentController@getComponentNodes');
    Route::post('component/edit/', 'ComponentController@updateComponent');
    Route::post('component/add/', 'ComponentController@addComponent');
    Route::post('component/delete/', 'ComponentController@deleteComponent');
    Route::get('componentoptions', 'ComponentController@getComponentOptions');
    Route::get('allcomponentoptions', 'ComponentController@getAllComponentOptions');
    Route::get('cascadecomponents', 'ComponentController@getCascadeComponents');

    Route::get('usernodes', 'UserController@getUserNodes');
    Route::post('user/edit/', 'UserController@updateUser');
    Route::post('user/add/', 'UserController@addUser');
    Route::post('user/delete/', 'UserController@deleteUser');

    Route::get('user/roleoptions', 'UserController@getRoleOptions');
    Route::get('user/groupoptions', 'UserController@getGroupOptions');
    Route::get('user/cascaderolesgroups', 'UserController@getCascadeRolesGroups');

   Route::get('user/lists', 'UserController@getusers');
    //Route::get('user/lists', 'UserController@index');

    Route::get('grpnodes', 'GroupResourcePermissionController@getGrpNodes');
    Route::post('grp/edit/', 'GroupResourcePermissionController@updateGrp');
    Route::post('grp/add/', 'GroupResourcePermissionController@addGrp');
    Route::post('grp/delete/', 'GroupResourcePermissionController@deleteGrp');

    Route::get('processes', 'ProcessController@show');
    Route::get('processnodes', 'ProcessController@getProcessNodes');
    Route::post('process/edit/', 'ProcessController@updateProcess');
    Route::post('process/add/', 'ProcessController@addProcess');
    Route::post('process/delete/', 'ProcessController@deleteProcess');
    Route::get('process/componentoptions', 'ProcessController@getComponentOptions');
    Route::get('process/cascadecomponentoptions', 'ProcessController@getCascadeComponentOptions');

    Route::get('permissions', 'PermissionController@show');
    Route::get('permissionnodes', 'PermissionController@getPermissionNodes');
    Route::post('permission/edit/', 'PermissionController@updatePermission');
    Route::post('permission/add/', 'PermissionController@addPermission');
    Route::post('permission/delete/', 'PermissionController@deletePermission');

    Route::get('devicetypes', 'DeviceTypeController@show');
    Route::get('devicetypenodes', 'DeviceTypeController@getDeviceTypeNodes');
    Route::post('devicetype/edit/', 'DeviceTypeController@updateDeviceType');
    Route::post('devicetype/add/', 'DeviceTypeController@addDeviceType');
    Route::post('devicetype/delete/', 'DeviceTypeController@deleteDeviceType');
    Route::get('devicetypeoptions', 'DeviceTypeController@getDeviceTypeOptions');

    Route::get('resourcetypes', 'ResourceTypeController@show');
    Route::get('resourcetypenodes', 'ResourceTypeController@getResourceTypeNodes');
    Route::post('resourcetype/edit/', 'ResourceTypeController@updateResourceType');
    Route::post('resourcetype/add/', 'ResourceTypeController@addResourceType');
    Route::post('resourcetype/delete/', 'ResourceTypeController@deleteResourceType');

    Route::get('responsivesettings', 'ResponsiveSettingController@show');
    Route::get('responsivesettingnodes', 'ResponsiveSettingController@getResponsiveSettingNodes');
    Route::post('responsivesetting/edit/', 'ResponsiveSettingController@updateResponsiveSetting');
    Route::post('responsivesetting/add/', 'ResponsiveSettingController@addResponsiveSetting');
    Route::post('responsivesetting/delete/', 'ResponsiveSettingController@deleteResponsiveSetting');

    Route::get('groupnodes', 'GroupController@getGroupNodes');
    Route::post('group/edit/', 'GroupController@updateGroup');
    Route::post('group/add/', 'GroupController@addGroup');
    Route::post('group/delete/', 'GroupController@deleteGroup');

    Route::get('cpmservicenodes', 'CpmServiceController@getCpmServiceNodes');
    Route::get('cpmservicegroupnodes', 'CpmServiceGroupController@getCpmServiceGroupNodes');
    Route::post('cpmservicegroup/update/', 'CpmServiceGroupController@updateCpmServiceGroup');
    Route::post('cpmservice/edit/', 'CpmServiceController@updateCpmService');
    Route::post('cpmservice/add/', 'CpmServiceController@addCpmService');
    Route::post('cpmservice/delete/', 'CpmServiceController@deleteCpmService');
    Route::get('cpmserviceoptions', 'CpmServiceController@getCpmServiceOptions');
    Route::get('cpmcascadeserviceoptions', 'CpmServiceController@getCpmCascadeServiceOptions');
    Route::get('cpmcascadeservicegroupoptions', 'CpmServiceController@getCpmCascadeServiceGroupOptions');
    Route::get('cpmserviceoptionsoflocation', 'CpmServiceController@getCpmServiceOptionsOfLocation');
    Route::get('cpmservicegroupotions', 'CpmServiceGroupController@getServiceGroupOptions');
    Route::get('cpmservicegroupoptionsoflocation', 'CpmServiceController@getCpmServiceGroupOptionsOfLocation');

//cpm2
Route::get('cpm2servicenodes', 'Cpm2Controller@getCpm2ServiceNodes');
//Route::get('cpm2servicenodes', 'CpmServiceController@getCpmServiceNodes');
Route::post('cpm2service/delete/', 'Cpm2Controller@deleteCpm2Service');
Route::post('cpm2service/add/', 'Cpm2Controller@addCpm2Service');
Route::post('cpm2service/edit/', 'Cpm2Controller@updateCpm2Service');
//Route::post('cpm2service/add/', 'CpmServiceController@addCpmService');//-------to check if vue files are ok--problem in php
    
//-----csticket
Route::post('/csticket/add', 'TicketCsController@addcsticket');
Route::post('/csticket/edit', 'TicketCsController@editcsticket');
Route::post('/csticket/displaycs', 'TicketCsController@displaycs');
Route::get('/csticket/getlastcsticket', 'TicketCsController@getlastcsticket');
//Route::post('/csticket/getpage', 'TickettypeController@getByPaginate');


Route::get('/csticket/getticketstatustable', 'TicketstatusController@getTicketStatusTable');
Route::post('/csticket/addstatustickettable', 'TicketstatusController@addTicketStatusTable');
Route::post('/csticket/updatestatus', 'TicketstatusController@updateTicketStatusTable');
Route::post('/csticket/deletestatus', 'TicketstatusController@deleteTicketStatusTable');
//Route::post('/csticket/deletestatus', 'Testb1Controller@deleteTest');


Route::get('/csticket/gettickettypetable', 'TickettypeController@getTicketTypeTable');
Route::post('/csticket/addtypetickettable', 'TickettypeController@addTicketTypeTable');
Route::post('/csticket/updatetype', 'TickettypeController@updateTicketTypeTable');
Route::post('/csticket/deletetype', 'TickettypeController@deleteTicketTypeTable');

Route::get('/csticket/ticketnodes', 'TicketCsController@getByPaginate');
Route::post('/csticket/deletecstkt', 'TicketCsController@deleteCsTicket');

Route::get('/csticket/usersaspergroups', 'GroupController@usersaspergroups');

Route::post('/csticket/addfile', 'TicketCsController@addFile');

Route::get('/csticket/tickettype1pagination', 'Tickettype1Controller@getByPaginate');










    Route::get('cpmactivitynodes', 'CpmActivityController@getCpmActivityNodes');
    Route::post('cpmactivity/edit/', 'CpmActivityController@updateCpmActivity');
    Route::post('cpmactivity/add/', 'CpmActivityController@addCpmActivity');
    Route::post('cpmactivity/delete/', 'CpmActivityController@deleteCpmActivity');
    Route::get('cpmactivityoptions', 'CpmActivityController@getCpmActivityOptions');
    Route::get('cpmactivityuseroptions', 'CpmActivityController@getAssociatedUserOptions');
    Route::get('cpmactivitygroupoptions', 'CpmActivityController@getAssociatedGroupOptions');
    Route::post('updateassociatedusers', 'CpmActivityController@updateAssociatedUsers');
    Route::get('cpmactivityassociatedusers', 'CpmActivityController@getAssociatedUsers');
    Route::get('cpmactivityassociatedmanagers', 'CpmActivityController@getAssociatedManagers');

    //cpm1
    Route::get('cpm1activitynodes', 'Cpm1Controller@getCpm1ActivityNodes');
    Route::post('cpm1activity/delete/', 'Cpm1Controller@deleteCpm1Activity');
   // Route::get('cpm1activitynodes', 'CpmActivityController@getCpmActivityNodes');

    Route::get('cpmmasternodes', 'CpmMasterController@getCpmMasterNodes');
    Route::post('cpmmaster/edit/', 'CpmMasterController@updateCpmMaster');
    Route::post('cpmmaster/add/', 'CpmMasterController@addCpmMaster');
    Route::post('cpmmaster/delete/', 'CpmMasterController@deleteCpmMaster');


    Route::get('resourcetype/resourcetypeoptions', 'ResourceTypeController@getResourceTypeOptions');
    Route::get('resource/resourceoptions', 'ResourceController@getResourceOptions');
    Route::get('resource/cascaderesourceoptions', 'ResourceController@getCascadeResourceOptions');
    Route::get('resource/resourcename', 'ResourceController@getResourceName');

    Route::get('permission/permissionoptions', 'PermissionController@getPermissionOptions');

    // customer tier level
    Route::get('customertierlevelnodes', 'CustomerTierLevelController@getCustomerTierLevelNodes');
    Route::post('customertierlevel/edit/', 'CustomerTierLevelController@updateCustomerTierLevel');
    Route::post('customertierlevel/add/', 'CustomerTierLevelController@addCustomerTierLevel');
    Route::post('customertierlevel/delete/', 'CustomerTierLevelController@deleteCustomerTierLevel');
    Route::get('customertierleveloptions', 'CustomerTierLevelController@getCustomerTierLevelOptions');
 

    // customer tier
    Route::get('customertiernodes', 'CustomerTierController@getCustomerTierNodes');
    Route::post('customertier/edit/', 'CustomerTierController@updateCustomerTier');
    Route::post('customertier/add/', 'CustomerTierController@addCustomerTier');
    Route::post('customertier/delete/', 'CustomerTierController@deleteCustomerTier');


    //order list
    Route::get('orderlist', 'OrderListController@getOrderList');
    Route::get('orderstatusoptions', 'OrderListController@getStatusOptions');
    Route::get('search', "OrderListController@getOrderDetailOnSearch");
    Route::get('attachments', 'AttachmentController@getAttachments');
    Route::get('downloadattachment', 'AttachmentController@downLoadAttachment');
    //order detail
    Route::get('orderdetail', "OrderDetailController@getDetails");
    //order item
    Route::get('orderitem', "OrderItemController@getOrderItems");
    //order bom fill item
    Route::get('orderbomfill', "OrderItemController@getBomFills");
    //order bom finish item
    Route::get('orderbomfinish', "OrderItemController@getBomFinishes");
    //order bom component item
    Route::get('orderbomcomp', "OrderItemController@getBomComponents");
    //associated jobs
    Route::get('associatedjobs', "AssociatedJobController@getAssociatedJobs");
    //all associated jobs
    Route::get('allassociatedjobs', "AssociatedJobController@getAllAssociatedJobs");
    Route::post('associatedjob/add/', 'AssociatedJobController@addAssociatedJob');
    Route::post('associatedjob/update/', 'AssociatedJobController@updateAssociatedJob');
    Route::post('associatedjob/delete/', 'AssociatedJobController@deleteAssociatedJob');

    // email templates
    Route::get('orderconfirmationtemplate', 'BookingConfirmationController@getOrderConfirmationEmailTemplate');
    Route::get('singleconfirmationemailtemplate', 'BookingConfirmationController@getSingleConfirmationEmailTemplate');
    Route::get('confirmationemailtemplate', 'BookingConfirmationController@getConfirmationEmailTemplate');
    Route::get('singleconfirmationtexttemplate', 'BookingConfirmationController@getSingleConfirmationTextTemplate');
    Route::get('expediteenquirytemplate', 'BookingConfirmationController@getExpediteEnquiryTemplate');
    // send email
    Route::post('sendemail', 'EmailController@sendEmail');
    Route::get('emailhistorylist', 'EmailController@getEmailHistoryList');
    // send text message via email
    Route::post('sendtextmessage', 'EmailController@sendTextMessage');

    // get note type actions
    Route::get('notetypes', 'NoteTypeController@getNoteTypeList');
    Route::get('notetypeactions', 'NoteTypeActionController@getNoteTypeActionList');
    Route::post('notetype/update/', 'NoteTypeController@update');
    Route::post('notetypeaction/update/', 'NoteTypeActionController@update');
    // get note type options
    Route::get('notetypeoptions', 'NoteTypeController@getNoteTypeOptions');
    Route::get('dowellnotetypeoptions', 'NoteTypeController@getDowellNoteTypeOptions');
    // get latest notes
    Route::get('latestnotes', 'CommentController@getLatestNotes');
    // customer notes
    Route::post('savecustomernotes', 'CommentController@saveCustomerNotes');
    Route::get('customernoteshistorylist', 'CommentController@getCustomerNotesHistoryList');
    // dowell notes
    Route::post('savedowellnotes', 'CommentController@saveDowellNotes');
    Route::get('dowellnoteshistorylist', 'CommentController@getDowellNotesHistoryList');
    // update requested delivery date
    Route::post('updatedeliverydate', 'BookingConfirmationController@updateRequestedDeliveryDate');
    Route::get('generatesystemdeliverydate', 'CpmOrderController@getSystemDeliveryDate');
    // confirmation
    Route::get('bookinginfo', 'BookingConfirmationController@getBookingInformation');
    Route::get('bookingconfirmationlist', 'BookingConfirmationController@getBookingConfirmationList');
    Route::post('printconfirmationlist', 'AssociatedJobController@printConfirmationList');
    Route::get('refreshselectedorder', 'BookingConfirmationController@getBookingInformation');

    // customer service
    Route::get('orderactivitylist', 'CpmOrderController@getOrderActivities');
    Route::get('activitiesoforder', 'CpmOrderController@getAllActivitiesOfOrder');
    // simulate cpm
    Route::post('simulatecpm', 'CpmOrderController@calculateCPM');
    // recalculatecpm
    Route::post('recalculatecpm', 'CpmOrderController@reCalculateCPM');
    // create cpm
    Route::post('createcpm', 'CpmOrderController@createCPM');
    // update cpm
    Route::post('updatecpm', 'CpmOrderController@updateCPM');
    // add ad hoc activity
    Route::post('addadhocactivity', 'CpmOrderController@addAdhocActivity');

    Route::get('latestactivitynotes', 'CpmCommentController@getLatestNotes');
    Route::get('getactivitystatuses', 'CpmServiceController@getStatuses');
    Route::post('saveactivitydowellnotes', 'CpmCommentController@saveDowellNotes');
    Route::get('activitynoteslist', 'CpmCommentController@getActivityNotesHistoryList');

    // change supervisor
    Route::get('customercontacts', 'CustomerContactController@getCustomerContacts');
    Route::post('changesupervisor', 'CustomerContactController@changeSupervisor');
    Route::post('newsupervisor', 'CustomerContactController@newSupervisor');

    // change delivery address
    Route::post('changeaddress', 'AddressController@changeAddress');

    // task mapping list
    Route::get('taskmappinglist', 'TaskMappingController@getTaskMappingList');
    Route::post('taskmapping/update/', 'TaskMappingController@update');

    // system default setting
    Route::get('defaultsettinglist', 'SystemSettingController@getDefaultSettingList');
    Route::post('defaultsetting/update/', 'SystemSettingController@update');
    Route::get('locationdefaultsettings', 'SystemSettingController@getSettingByLocation');

    // log list
    Route::get('loglist', 'LogController@getLogList');

    // associatedtasks
    Route::get('associatedtasks', 'CpmOrderController@getAssociatedTasks');

    //--------------------cstypes---------------------
    Route::post('/csticket/addtype1tickettable', 'Tickettype1Controller@addTicketType1Table');
    Route::post('/csticket/gettype1tickettable', 'Tickettype1Controller@getTicketType1Table');
//---------------------------
    Route::post('/csticket/deletetype1', 'Tickettype1Controller@deleteTicketType1');
    Route::post('/csticket/gettype1tickettable1', 'TicketCsController@gettype1ticket');
    Route::post('/csticket/updatetype1', 'Tickettype1Controller@updateTicketType1');
    Route::post('/csticket/latestcscomments', 'TicketCommentController@latestcscomments');
  //  Route::get('latestactivitynotes', 'CpmCommentController@getLatestNotes');

    Route::post('/csticket/addtype2Atickettable', 'Tickettype2AController@addTicketType2ATable');
    Route::post('/csticket/gettype2Atickettable', 'TicketCsController@gettype2Aticket');
    Route::post('/csticket/deletetype2A', 'Tickettype2AController@deleteTicketType2A');
    Route::post('/csticket/updatetype2A', 'Tickettype2AController@updateTicketType2A');

    Route::get('/csticket/getticketcnstatustable', 'TicketcnstatusController@getTicketCnStatusTable');
    Route::post('/csticket/addcnstatustickettable', 'TicketcnstatusController@addTicketCnStatusTable');
    Route::post('/csticket/updatecnstatus', 'TicketcnstatusController@updateTicketCnStatusTable');
    Route::post('/csticket/deletecnstatus', 'TicketcnstatusController@deleteTicketCnStatusTable');

    Route::post('/csticket/addtype3tickettable', 'Tickettype3Controller@addTicketType3Table');
    Route::post('/csticket/gettype3tickettable', 'TicketCsController@gettype3ticket');
    Route::post('/csticket/deletetype3', 'Tickettype3Controller@deleteTicketType3');
    Route::post('/csticket/updatetype3', 'Tickettype3Controller@updateTicketType3');

    Route::post('/csticket/addtype4tickettable', 'Tickettype4Controller@addTicketType4Table');
    Route::post('/csticket/gettype4tickettable', 'TicketCsController@gettype4ticket');
    Route::post('/csticket/deletetype4', 'Tickettype3Controller@deleteTicketType4');
    Route::post('/csticket/updatetype4', 'Tickettype3Controller@updateTicketType4');
    
});
