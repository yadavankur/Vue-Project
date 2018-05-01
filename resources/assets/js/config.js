const apiDomain = Laravel.apiDomain;
export const companyName = Laravel.companyName;
export const siteName = Laravel.siteName;
export const siteVersion = 'Ver1.0';
export const projectName = Laravel.projectName;
export const homeUrlPath = '/dashboard';
export const publicHoliday = 'PUBLIC HOLIDAY';

// check permission
export const checkpermission = apiDomain + '/checkpermission';
// playground
export const runPhpCode = apiDomain + '/runcode';

// dashboard
export const currentNotificationList = apiDomain + '/notificationlist';
export const currentTaskList = apiDomain + '/tasklist';
export const readNotification = apiDomain + '/updatenotificationstatus';
export const taskOverviewBarchart = apiDomain + '/taskoverviewbarchart';
export const taskOverviewDoughnutchart = apiDomain + '/taskoverviewdoughnutchart';

// business calendar
export const currentBusinessCalendarList = apiDomain + '/businesscalendarlist';
export const currentBusinessCalendarTypes = apiDomain + '/businesscalendartypes';
export const getBusinessCalendarFilterOptions = apiDomain + '/businesscalendarfilteroptions';
export const saveBusinessCalendar = apiDomain + '/savebusinesscalendar';
export const updateBusinessCalendar = apiDomain + '/updatebusinesscalendar';
export const deleteBusinessCalendar = apiDomain + '/deletebusinesscalendar';
// user
export const login = apiDomain + '/authenticate';
export const currentUser = apiDomain + '/user';
export const currentUsers = apiDomain + '/users';
export const currentUserNodes = apiDomain + '/usernodes';
export const updateUser = apiDomain + '/user/edit';
export const addUser = apiDomain + '/user/add';
export const deleteUser = apiDomain + '/user/delete';
export const roleOptions = apiDomain + '/user/roleoptions';
export const groupOptions = apiDomain + '/user/groupoptions';
export const cascadeRolesGroups = apiDomain + '/user/cascaderolesgroups';
export const userlists = apiDomain + '/user/lists';

// menu
export const currentMenus = apiDomain + '/menus';
export const currentMenuNodes = apiDomain + '/menunodes';
export const updateMenu = apiDomain + '/menu/edit';
export const addMenu = apiDomain + '/menu/add';
export const deleteMenu = apiDomain + '/menu/delete';
export const getBadgeCount = apiDomain + '/getbadgecount';

// tab
export const currentTabs = apiDomain + '/tabs';  //show function in controller
export const currentTabNodes = apiDomain + '/tabnodes';
export const updateTab = apiDomain + '/tab/edit';
export const addTab = apiDomain + '/tab/add';
export const deleteTab = apiDomain + '/tab/delete';
//tab1
export const currentTab1Nodes = apiDomain + '/tab1nodes';

// process
export const currentProcesses = apiDomain + '/processes';
export const currentProcessNodes = apiDomain + '/processnodes';
export const updateProcess = apiDomain + '/process/edit';
export const addProcess = apiDomain + '/process/add';
export const deleteProcess = apiDomain + '/process/delete';
export const processComponentOptions = apiDomain + '/process/componentoptions';
export const processCascadeComponentOptions = apiDomain + '/process/cascadecomponentoptions';

// role
export const currentRoles = apiDomain + '/roles';
export const currentRoleNodes = apiDomain + '/rolenodes';
export const updateRole = apiDomain + '/role/edit';
export const addRole = apiDomain + '/role/add';
export const deleteRole = apiDomain + '/role/delete';
export const allRoleOptions = apiDomain + '/role/allroleoptions';

// page
export const currentPages = apiDomain + '/pages';
export const currentPageNodes = apiDomain + '/pagenodes';
export const updatePage = apiDomain + '/page/edit';
export const addPage = apiDomain + '/page/add';
export const deletePage = apiDomain + '/page/delete';
export const pageOptions = apiDomain + '/pageoptions';

//state
export const currentStateNodes = apiDomain + '/statenodes';
export const updateState = apiDomain + '/state/edit';
export const addState = apiDomain + '/state/add';
export const deleteState = apiDomain + '/state/delete';
export const stateOptions = apiDomain + '/stateoptions';

export const currentStateNodesnew2 = apiDomain + '/statenodesnew2';
export const addTest = apiDomain + '/test/add';
export const deleteTest = apiDomain + '/test/delete';
export const updateTest =apiDomain + '/test/edit';

//group resource permission
export const currentGrpNodes = apiDomain + '/grpnodes';
export const updateGrp = apiDomain + '/grp/edit';
export const addGrp = apiDomain + '/grp/add';
export const deleteGrp = apiDomain + '/grp/delete';
export const resourceTypeOptions = apiDomain + '/resourcetype/resourcetypeoptions';
export const resourceOptions = apiDomain + '/resource/resourceoptions';
export const permissionOptions = apiDomain + '/permission/permissionoptions';
export const resourceName = apiDomain + '/resource/resourcename';
export const cascadeResourceOptions = apiDomain + '/resource/cascaderesourceoptions';

// location
export const currentLocations = apiDomain + '/locations';
export const currentLocationNodes = apiDomain + '/locationnodes';
export const updateLocation = apiDomain + '/location/edit';
export const addLocation = apiDomain + '/location/add';
export const deleteLocation = apiDomain + '/location/delete';
export const locationOptions = apiDomain + '/locationoptions';
export const assignedlocationOptions = apiDomain + '/assignedlocationoptions';
export const cascadeLocationOptions = apiDomain + '/cascadelocationoptions';
export const assignedCascadelocationOptions = apiDomain + '/assignedcascadelocationoptions';

// component
export const currentComponents = apiDomain + '/components';
export const currentComponentNodes = apiDomain + '/componentnodes';
export const updateComponent = apiDomain + '/component/edit';
export const addComponent = apiDomain + '/component/add';
export const deleteComponent = apiDomain + '/component/delete';
export const componentOptions = apiDomain + '/componentoptions';
export const allcomponentOptions = apiDomain + '/allcomponentoptions';
export const cascadeComponents = apiDomain + '/cascadecomponents';
// permission
export const currentPermissions = apiDomain + '/permissions';
export const currentPermissionNodes = apiDomain + '/permissionnodes';



export const updatePermission = apiDomain + '/permission/edit';
export const addPermission = apiDomain + '/permission/add';
export const deletePermission = apiDomain + '/permission/delete';

// device type
export const currentDeviceTypes = apiDomain + '/devicetypes';
export const currentDeviceTypeNodes = apiDomain + '/devicetypenodes';
export const updateDeviceType = apiDomain + '/devicetype/edit';
export const addDeviceType = apiDomain + '/devicetype/add';
export const deleteDeviceType = apiDomain + '/devicetype/delete';
export const deviceTypeOptions = apiDomain + '/devicetypeoptions';

// resource type
export const currentResourceTypes = apiDomain + '/resourcetypes';
export const currentResourceTypeNodes = apiDomain + '/resourcetypenodes';
export const updateResourceType = apiDomain + '/resourcetype/edit';
export const addResourceType = apiDomain + '/resourcetype/add';
export const deleteResourceType = apiDomain + '/resourcetype/delete';

// responsive type
export const currentResponsiveSettings = apiDomain + '/responsivesettings';
export const currentResponsiveSettingNodes = apiDomain + '/responsivesettingnodes';
export const updateResponsiveSetting = apiDomain + '/responsivesetting/edit';
export const addResponsiveSetting = apiDomain + '/responsivesetting/add';
export const deleteResponsiveSetting = apiDomain + '/responsivesetting/delete';

// group
export const currentGroups = apiDomain + '/groups';
export const currentGroupNodes = apiDomain + '/groupnodes';
export const updateGroup = apiDomain + '/group/edit';
export const addGroup = apiDomain + '/group/add';
export const deleteGroup = apiDomain + '/group/delete';

// booking module

// CPM service
export const currentCpmServices = apiDomain + '/cpmservices';
export const currentCpmServiceNodes = apiDomain + '/cpmservicenodes';
export const updateCpmService = apiDomain + '/cpmservice/edit';
export const addCpmService = apiDomain + '/cpmservice/add';
export const deleteCpmService = apiDomain + '/cpmservice/delete';
export const cpmServiceOptions = apiDomain + '/cpmserviceoptions';
export const cpmCascadeServiceOptions = apiDomain + '/cpmcascadeserviceoptions';
export const currentCpmServiceGroupNodes = apiDomain + '/cpmservicegroupnodes';
export const updateCpmServiceGroup = apiDomain + '/cpmservicegroup/update';
export const cpmCascadeServiceGroupOptions = apiDomain + '/cpmcascadeservicegroupoptions';
export const cpmServiceGroupOptions = apiDomain + '/cpmservicegroupotions';

//cpm2
export const currentCpm2ServiceNodes = apiDomain + '/cpm2servicenodes';
export const deleteCpm2Service = apiDomain + '/cpm2service/delete';
export const addCpm2Service = apiDomain + '/cpm2service/add';
export const updateCpm2Service = apiDomain + '/cpm2service/edit';
//export const currentCpm2ServiceNodes = apiDomain + '/cpmservicenodes';

//csticket
//addCsTicketRequest
//export const currentCsTable = apiDomain + '/cpm1activitynodes';
//export const currentCsTable = apiDomain + '/csticket/getpage';


export const addcsticket = apiDomain + '/csticket/add';
export const editcsticket = apiDomain + '/csticket/edit';
export const displaycs = apiDomain + '/csticket/displaycs';
export const getlastcsticket = apiDomain + '/csticket/getlastcsticket';

export const getticketstatustableapi = apiDomain + '/csticket/getticketstatustable';
export const addcsstatus = apiDomain + '/csticket/addstatustickettable';
export const updatestatus = apiDomain + '/csticket/updatestatus';
export const deletestatus = apiDomain + '/csticket/deletestatus';

export const gettickettypetableapi = apiDomain + '/csticket/gettickettypetable';
export const addcstype = apiDomain + '/csticket/addtypetickettable';
export const updatetype = apiDomain + '/csticket/updatetype';
export const deletetype = apiDomain + '/csticket/deletetype';
//cstkt
export const currentTicketNodes = apiDomain + '/csticket/ticketnodes';
export const deletecstkt = apiDomain + '/csticket/deletecstkt';
export const useraspergroups = apiDomain + '/csticket/usersaspergroups';
export const addFile = apiDomain + '/csticket/addfile';


export const addcstype1 = apiDomain + '/csticket/addtype1tickettable';
export const gettickettype1tableapi = apiDomain + '/csticket/gettype1tickettable';
export const tickettype1pagination = apiDomain + '/csticket/tickettype1pagination';




// CPM activity
export const currentCpmActivities = apiDomain + '/cpmactivities';
export const currentCpmActivityNodes = apiDomain + '/cpmactivitynodes';
export const updateCpmActivity = apiDomain + '/cpmactivity/edit';
export const addCpmActivity = apiDomain + '/cpmactivity/add';
export const deleteCpmActivity = apiDomain + '/cpmactivity/delete';
export const cpmActivityOptions = apiDomain + '/cpmactivityoptions';
export const cpmActivityUserOptions = apiDomain + '/cpmactivityuseroptions';
export const cpmActivityGroupOptions = apiDomain + '/cpmactivitygroupoptions';
export const updateAssociatedUsers = apiDomain + '/updateassociatedusers';
export const cpmActivityAssociatedUsers = apiDomain + '/cpmactivityassociatedusers';
export const cpmActivityAssociatedManagers = apiDomain + '/cpmactivityassociatedmanagers';

//cpm1
export const currentCpm1ActivityNodes = apiDomain + '/cpm1activitynodes';
export const deleteCpm1Activity = apiDomain + '/cpm1activity/delete';
export const addCpm1Activity = apiDomain + '/cpm1activity/add';


// CPM service
export const currentCpmMasters = apiDomain + '/cpmmasters';
export const currentCpmMasterNodes = apiDomain + '/cpmmasternodes';
export const updateCpmMaster = apiDomain + '/cpmmaster/edit';
export const addCpmMaster = apiDomain + '/cpmmaster/add';
export const deleteCpmMaster = apiDomain + '/cpmmaster/delete';

// booking module
// customer tier level
export const currentCustomerTierLevels = apiDomain + '/customertierlevels';
export const currentCustomerTierLevelNodes = apiDomain + '/customertierlevelnodes';
export const updateCustomerTierLevel = apiDomain + '/customertierlevel/edit';
export const addCustomerTierLevel = apiDomain + '/customertierlevel/add';
export const deleteCustomerTierLevel = apiDomain + '/customertierlevel/delete';
export const customerTierLevelOptions = apiDomain + '/customertierleveloptions';
// customer tier
export const currentCustomerTiers = apiDomain + '/customertiers';
export const currentCustomerTierNodes = apiDomain + '/customertiernodes';
export const updateCustomerTier = apiDomain + '/customertier/edit';
export const addCustomerTier = apiDomain + '/customertier/add';
export const deleteCustomerTier = apiDomain + '/customertier/delete';

// orderlist
export const currentOrderList = apiDomain + '/orderlist';
export const currentOrderStatusOptions= apiDomain + '/orderstatusoptions';
// orderdetail
export const currentOrderDetail = apiDomain + '/orderdetail';
// orderitem
export const currentOrderItem = apiDomain + '/orderitem';
// order bomfill
export const currentOrderBomFill = apiDomain + '/orderbomfill';
// order finish
export const currentOrderBomFinish = apiDomain + '/orderbomfinish';
// order bom component
export const currentOrderBomComp = apiDomain + '/orderbomcomp';
// booking
export const currentBookingData = apiDomain + '/booking';
export const getOrderDetailOnSearch = apiDomain + '/search';
export const getOrderAttachments = apiDomain + '/attachments';
export const currentAssociatedJobsList = apiDomain + '/associatedjobs';
export const currentAssociatedJobsAll = apiDomain + '/allassociatedjobs';
// add associated jobs
export const addAssociatedJob = apiDomain + '/associatedjob/add';
// update associated job
export const updateAssociatedJob = apiDomain + '/associatedjob/update';
// delete associated job
export const deleteAssociatedJob = apiDomain + '/associatedjob/delete';

// send email & expedite enquiry
export const sendEmailRequest = apiDomain + '/sendemail';
export const currentEmailHistoryList = apiDomain + '/emailhistorylist';
// // send expedite enquiry
// export const sendExpediteEnquiry = apiDomain + '/sendexpediteenquiry';
// export const currentExpediteHistoryList = apiDomain + '/emailexpeditehistorylist';
// notes
// get note types
export const currentNoteTypeList = apiDomain + '/notetypes';
export const currentNoteTypeActionList = apiDomain + '/notetypeactions';
export const updateNoteType = apiDomain + '/notetype/update';
export const updateNoteTypeAction = apiDomain + '/notetypeaction/update';

// note type options
export const getNoteTypeOptions = apiDomain + '/notetypeoptions';
export const getDowellNoteTypeOptions = apiDomain + '/dowellnotetypeoptions';
export const currentLatestNotes = apiDomain + '/latestnotes';
// customer notes
export const saveCustomerNotesRequest = apiDomain + '/savecustomernotes';
export const currentCustomerNotesHistoryList = apiDomain + '/customernoteshistorylist';

// dowell notes
export const saveDowellNotesRequest = apiDomain + '/savedowellnotes';
export const currentDowellNotesHistoryList = apiDomain + '/dowellnoteshistorylist';

export const updateDeliveryDateRequest = apiDomain + '/updatedeliverydate';

export const getOrderConfirmationEmailTemplate = apiDomain + '/orderconfirmationtemplate';
export const getConfirmationEmailTemplate = apiDomain + '/confirmationemailtemplate';
export const getSingleConfirmationEmailTemplate = apiDomain + '/singleconfirmationemailtemplate';
export const getSingleConfirmationTextTemplate = apiDomain + '/singleconfirmationtexttemplate';
export const getExpediteEnquiryTemplate = apiDomain + '/expediteenquirytemplate';
//download files
export const downloadAttachment = apiDomain + '/downloadattachment';
export const getSystemGeneratedDeliveryDate = apiDomain + '/generatesystemdeliverydate';

// confirmation
export const currentBookingInfo = apiDomain + '/bookinginfo';
export const currentConfirmationList = apiDomain + '/bookingconfirmationlist';
export const getPrintConfirmationList = apiDomain + '/printconfirmationlist';
export const refreshSelectedOrder = apiDomain + '/refreshselectedorder';

// send text message
export const sendTextMessageRequest = apiDomain + '/sendtextmessage';
export const currentTextMessageHistoryList = apiDomain + '/textmessagehistorylist';

// customer service
export const currentOrderActivityList = apiDomain + '/orderactivitylist';
export const getactivitystatuses = apiDomain + '/getactivitystatuses';
export const currentActivitiesOfOrder = apiDomain + '/activitiesoforder';
// get service options of location
export const getServiceOptionsOfLocation = apiDomain + '/cpmserviceoptionsoflocation';
export const getCascadeServiceGroupOptionsOfLocation = apiDomain + '/cpmservicegroupoptionsoflocation';

// simulate CPM
export const simulateCPM = apiDomain + '/simulatecpm';
// recalculate CPM
export const reCalculateCPM = apiDomain + '/recalculatecpm';

// update CPM
export const updateCPMRequest = apiDomain + '/updatecpm';
// create CPM
export const createCPMRequest = apiDomain + '/createcpm';
// insert ad hoc activity
export const addAdhocActivityRequest = apiDomain + '/addadhocactivity';
// activity notes
export const currentLatestActivityNotes = apiDomain + '/latestactivitynotes';
// save activity notes
export const saveActivityDowellNotesRequest = apiDomain + '/saveactivitydowellnotes';
export const currentCPMActivityNotesHistoryList = apiDomain + '/activitynoteslist';

// change supervisor on the fly
export const currentCustomerContactList = apiDomain + '/customercontacts';
export const changeSupervisorRequest = apiDomain + '/changesupervisor';
export const newSupervisorRequest = apiDomain + '/newsupervisor';

// change delivery address on the fly
export const changeAddressRequest = apiDomain + '/changeaddress';

// task mapping
export const currentTaskMappingList = apiDomain + '/taskmappinglist';
export const updateTaskMapping = apiDomain + '/taskmapping/update';

// default setting (system setting)
export const currentDefaultSettingList = apiDomain + '/defaultsettinglist';
export const updateDefaultSetting = apiDomain + '/defaultsetting/update';
export const locationDefaultSettings = apiDomain + '/locationdefaultsettings';

// reset password
export const retrievePassword = apiDomain + '/retrievepassword';
export const resetPassword = apiDomain + '/resetpassword';

// logs
export const currentLogList = apiDomain + '/loglist';

// associated tasks
export const currentAssociatedTasksList = apiDomain + '/associatedtasks';
//-------------------------------------------------------------------------------

export const deletetype1 = apiDomain + '/csticket/deletetype1';
export const gettickettype1tableapi1 = apiDomain + '/csticket/gettype1tickettable1';

export const updatetype1 = apiDomain + '/csticket/updatetype1';
export const latestcscomments = apiDomain + '/csticket/latestcscomments';
//-----------------
export const addcstype2A = apiDomain + '/csticket/addtype2Atickettable';
export const gettickettype2Atableapi = apiDomain + '/csticket/gettype2Atickettable';
export const deletetype2A = apiDomain + '/csticket/deletetype2A';
export const updatetype2A = apiDomain + '/csticket/updatetype2A';

export const getticketcnstatustableapi = apiDomain + '/csticket/getticketcnstatustable';
export const addcscnstatus = apiDomain + '/csticket/addcnstatustickettable';
export const updatecnstatus = apiDomain + '/csticket/updatecnstatus';
export const deletecnstatus = apiDomain + '/csticket/deletecnstatus';
//---------------
export const addcstype3 = apiDomain + '/csticket/addtype3tickettable';
export const gettickettype3tableapi = apiDomain + '/csticket/gettype3tickettable';
export const deletetype3 = apiDomain + '/csticket/deletetype3';
export const updatetype3 = apiDomain + '/csticket/updatetype3';

export const addcstype4 = apiDomain + '/csticket/addtype4tickettable';
export const gettickettype4tableapi = apiDomain + '/csticket/gettype4tickettable';
export const deletetype4 = apiDomain + '/csticket/deletetype4';
export const updatetype4 = apiDomain + '/csticket/updatetype4';



