insert into cpm_services("name", "location_id") values('main service', 5);
insert into cpm_services("name", "location_id") values('Rectification service', 5);
insert into cpm_services("name", "location_id") values('General service call up', 5);
-- master activites
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Booking Job', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Transfer to Capital/Schedule/AS400', 1, 0);
-- site measure options begin
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Review service schedule for levelling', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Confirm with customer', 1, 1);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Print service paperwork', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Allocate run and serviceman', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Conduct service', 1, 1);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Return paperwork to office and update order sizes in V6', 1, 1);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Close service job', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Update capital', 1, 0);
-- site measure options end
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Allocate stock items', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Order non stock items', 1, 1);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Order special colour', 1, 1);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Order buyin items', 1, 1);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Review production schedule for levelling', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Confirm job with customer - production', 1, 1);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Set schedule order(truck runs incl areas)', 1, 1);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Order short lead time items (Glass & Galleries', 1, 1);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Print production paperwork', 1, 1);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Send paperwork to factory', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Receipt buyin (non stock items, components& special colours)', 1, 1);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Pick metal & components & glass', 1, 1);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Cut metal', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Cut Reveals', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Cut in house', 1, 0);
---- prepare glass -- begin
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Receive delivery from viridian', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Sort into jobs lots', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Deliver to line', 1, 0);
---- prepare glass -- end
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Assembly of job (Incl glass)', 1, 1);
---- service install -- begin
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Scope Job (Incl parts required)', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Review service schedule for levelling', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Confirm with customer', 1, 1);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Schedule & allocate runs & servicemen', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Receipt parts into service area', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Print paperwork', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Load vans', 1, 0);
---- service install -- end
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Receipt buyin finished goods', 1, 1);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Allocate truck runs', 1, 1);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Load products onto L Frame', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Deliver product', 1, 1);
---- service install -- begin
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Conduct job', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Complete', 1, 1);
---- service install -- end
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(1, 'Invoice', 1, 1);

----- Rectification

insert into cpm_activities("service_id", "name", "duration", "tick_option") values(2, 'Raise paperwork', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(2, 'Transfer to Capital', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(2, 'Scope job incl parts required', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(2, 'Review schedule for levelling', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(2, 'Confirm date with customer', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(2, 'Set schedule order (service vans incl areas)', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(2, 'Print service paperwork', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(2, 'Send paperwork to service personnel', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(2, 'Conduct service', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(2, 'Complete in system', 1, 0);

----- General service callup
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(3, 'Review schedule for levelling', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(3, 'Confirm date with customer', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(3, 'Set schedule order (service vans incl areas)', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(3, 'Print service paperwork', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(3, 'Send paperwork to service personnel', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(3, 'Conduct service', 1, 0);
insert into cpm_activities("service_id", "name", "duration", "tick_option") values(3, 'Complete in system', 1, 0);

--- CPM Master table
-- insert into cpm_masters("activity_id", "predecessor_id", "successor_id") values(1, null, );

insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 1, null, 2);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 2, 1, 3);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 2, 1, 12);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 2, 1, 13);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 2, 1, 14);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 2, 1, 15);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 3, 2, 4);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 4, 3, 5);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 5, 4, 6);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 6, 5, 7);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 7, 6, 8);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 8, 7, 9);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 9, 8, 10);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 10, 9, 11);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 10, 9, 12);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 10, 9, 13);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 10, 9, 14);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 10, 9, 15);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 11, 2, 16);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 11, 2, 30);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 11, 10, 16);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 11, 10, 30);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 12, 2, 21);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 12, 10, 21);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 13, 2, 21);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 13, 10, 21);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 14, 2, 37);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 14, 10, 37);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 15, 2, 16);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 15, 10, 16);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 16, 11, 17);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 16, 15, 17);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 17, 16, 18);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 18, 17, 19);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 18, 17, 26);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 19, 18, 20);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 20, 19, 22);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 20, 19, 25);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 21, 12, 22);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 21, 13, 22);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 22, 20, 23);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 22, 20, 24);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 22, 21, 23);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 22, 21, 24);

insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 23, 22, 29);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 24, 22, 29);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 25, 20, 27);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 26, 18, 27);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 27, 25, 28);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 27, 26, 28);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 28, 27, 29);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 29, 23, 36);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 29, 24, 36);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 29, 28, 36);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 29, 23, 38);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 29, 24, 38);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 29, 28, 38);

insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 30, 11, 31);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 31, 30, 32);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 32, 31, 33);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 33, 32, 34);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 34, 33, 35);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 35, 34, 36);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 36, 29, 41);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 36, 35, 41);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 37, 14, 38);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 38, 29, 39);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 38, 37, 39);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 39, 38, 40);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 40, 39, 41);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 40, 39, 43);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 41, 36, 42);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 41, 40, 42);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 42, 41, 43);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 43, 40, null);
insert into cpm_masters(service_id, activity_id, predecessor_id, successor_id) values(1, 43, 42, null);


