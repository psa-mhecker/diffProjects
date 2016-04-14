DROP TABLE psa_webservice_account_package;
DROP TABLE psa_webservice_action_package;
DROP TABLE psa_webservice_account;
DROP TABLE psa_webservice_action;
DROP TABLE psa_webservice_package;
DROP TABLE psa_webservice;
DROP TABLE psa_user_page_zone;
DROP TABLE psa_user_page;
DROP TABLE psa_terms_group_rel;
DROP TABLE psa_terms_relationships;
DROP TABLE psa_terms;
DROP TABLE psa_terms_group;
DROP TABLE psa_service_page;
DROP TABLE psa_service;
DROP TABLE psa_service_type;
DROP TABLE psa_profile_subscriber;
DROP TABLE psa_perso_profile_page;
DROP TABLE psa_mobapp_content;
DROP TABLE psa_mobapp_content_type;
DROP TABLE psa_mobapp_site_home;
DROP TABLE psa_mobapp_site;
DROP TABLE psa_formbuilder_mail_media;
DROP TABLE psa_formbuilder_mail;
DROP TABLE psa_formbuilder_value;
DROP TABLE psa_formbuilder;
DROP TABLE psa_acl_role_functionality;
DROP TABLE psa_acl_role_user;
DROP TABLE psa_acl_role;
DROP TABLE psa_acl_functionality;
DROP TABLE psa_acl_functionality_type;
DROP TABLE psa_user_zone_template;


delete from psa_user_role where USER_LOGIN != 'admin';
delete from psa_user_profile where USER_LOGIN != 'admin';
delete from psa_user where USER_LOGIN != 'admin';
TRUNCATE TABLE psa_comment_blacklist;# MySQL a retourné un résultat vide (aucune ligne).
TRUNCATE TABLE psa_lock;# MySQL a retourné un résultat vide (aucune ligne).
TRUNCATE TABLE psa_media_format_intercept;# MySQL a retourné un résultat vide (aucune ligne).
TRUNCATE TABLE psa_paragraph_media;# MySQL a retourné un résultat vide (aucune ligne).
TRUNCATE TABLE psa_paragraph;# MySQL a retourné un résultat vide (aucune ligne).
TRUNCATE TABLE psa_rewrite;# MySQL a retourné un résultat vide (aucune ligne).
TRUNCATE TABLE psa_rss_feed;# MySQL a retourné un résultat vide (aucune ligne).
truncate table psa_barre_outils;
DELETE  from psa_zone_layout;
truncate table psa_form;
delete from psa_build;


DELETE from psa_content_version_media where content_id in (select content_id from psa_content where SITE_ID > 2);
DELETE from psa_content_version_content where content_id in (select content_id from psa_content where SITE_ID > 2);
DELETE from psa_content_zone where content_id in (select content_id from psa_content where SITE_ID > 2);
DELETE from psa_content_zone_media where content_id in (select content_id from psa_content where SITE_ID > 2);
DELETE from psa_content_zone_multi where content_id in (select content_id from psa_content where SITE_ID > 2);
DELETE from psa_content_version where content_id in (select content_id from psa_content where SITE_ID > 2);
DELETE from psa_content_version_media where content_id in (select content_id from psa_content where SITE_ID is null);
DELETE from psa_content_version_content where content_id in (select content_id from psa_content where SITE_ID is null);
DELETE from psa_content_zone where content_id in (select content_id from psa_content where SITE_ID is null);
DELETE from psa_content_zone_media where content_id in (select content_id from psa_content where SITE_ID is null);
DELETE from psa_content_zone_multi where content_id in (select content_id from psa_content where SITE_ID is null);
DELETE from psa_content_version where content_id in (select content_id from psa_content where SITE_ID is null);
DELETE from psa_content_version_media where content_id not in (select content_id from psa_content);
DELETE from psa_content_version_content where content_id not in (select content_id from psa_content);
DELETE from psa_content_zone where content_id not in (select content_id from psa_content);
DELETE from psa_content_zone_media where content_id not in (select content_id from psa_content);
DELETE from psa_content_zone_multi where content_id not in (select content_id from psa_content);
DELETE from psa_content_version where content_id not in (select content_id from psa_content);
DELETE from psa_content where SITE_ID > 2;
DELETE from psa_content where SITE_ID is null;
DELETE from psa_content_version_media where LANGUE_ID > 2;
DELETE from psa_content_version_content where LANGUE_ID > 2;
DELETE from psa_content_zone where LANGUE_ID > 2;
DELETE from psa_content_zone_media where LANGUE_ID > 2;
DELETE from psa_content_zone_multi where LANGUE_ID > 2;
DELETE from psa_content_version where LANGUE_ID > 2;
DELETE from psa_content where LANGUE_ID > 2;
DELETE from psa_page_order where page_id in (select page_id from psa_page where SITE_ID > 2);
DELETE from psa_page_version_media where page_id in (select page_id from psa_page where SITE_ID > 2);
DELETE from psa_page_version_content where page_id in (select page_id from psa_page where SITE_ID > 2);
DELETE from psa_page_zone where page_id in (select page_id from psa_page where SITE_ID > 2);
DELETE from psa_page_zone_media where page_id in (select page_id from psa_page where SITE_ID > 2);
DELETE from psa_page_zone_multi where page_id in (select page_id from psa_page where SITE_ID > 2);
DELETE from psa_navigation where page_id in (select page_id from psa_page where SITE_ID > 2);
DELETE from psa_page_version where page_id in (select page_id from psa_page where SITE_ID > 2);
DELETE from psa_page_order where page_id not in (select page_id from psa_page);
DELETE from psa_page_version_media where page_id not in (select page_id from psa_page);
DELETE from psa_page_version_content where page_id not in (select page_id from psa_page);
DELETE from psa_page_zone where page_id not in (select page_id from psa_page);
DELETE from psa_page_zone_media where page_id not in (select page_id from psa_page);
DELETE from psa_page_zone_multi where page_id not in (select page_id from psa_page);
DELETE from psa_navigation where page_id not in (select page_id from psa_page);
DELETE from psa_page_version where page_id not in (select page_id from psa_page);
DELETE from psa_page where SITE_ID > 2;
DELETE from psa_page_order where LANGUE_ID > 2;
DELETE from psa_page_version_media where LANGUE_ID > 2;
DELETE from psa_page_version_content where LANGUE_ID > 2;
DELETE from psa_page_zone where LANGUE_ID > 2;
DELETE from psa_page_zone_media where LANGUE_ID > 2;
DELETE from psa_page_zone_multi where LANGUE_ID > 2;
DELETE from psa_navigation where LANGUE_ID > 2;
DELETE from psa_page_version where LANGUE_ID > 2;
DELETE from psa_page where LANGUE_ID > 2;
delete from psa_research where SITE_ID>2;
delete from psa_research where LANGUE_ID>2;
delete from psa_research_param_field where SITE_ID>2;
delete from psa_research_param where SITE_ID>2;
#select distinct media_id from psa_page_zone_multi where media_id in (select media_id from psa_media where media_directory_id in (select media_directory_id from psa_media_directory where SITE_ID>2))
#select distinct media_id from psa_page_multi_zone_multi where media_id in (select media_id from psa_media where media_directory_id in (select media_directory_id from psa_media_directory where SITE_ID>2))
#select distinct media_id from psa_page_multi_zone where media_id in (select media_id from psa_media where media_directory_id in (select media_directory_id from psa_media_directory where SITE_ID>2))
#select distinct media_id from psa_page_zone where media_id in (select media_id from psa_media where media_directory_id in (select media_directory_id from psa_media_directory where SITE_ID>2))
#select distinct media_id from psa_page_zone_media where media_id in (select media_id from psa_media where media_directory_id in (select media_directory_id from psa_media_directory where SITE_ID>2))
#select distinct media_id from psa_page_version where media_id in (select media_id from psa_media where media_directory_id in (select media_directory_id from psa_media_directory where SITE_ID>2))
#select distinct media_id from psa_page_multi_zone_media where media_id in (select media_id from psa_media where media_directory_id in (select media_directory_id from psa_media_directory where SITE_ID>2))
#select distinct media_id from psa_media_directory where media_id in (select media_id from psa_media where media_directory_id in (select media_directory_id from psa_media_directory where SITE_ID>2))

#delete from psa_page_zone_multi;
update psa_media set media_directory_id = 475 where media_id in (299,301,304,305,404,954,982,1116,4523,5163,5169,5170,5172,5181,5182,5183,5185,127,129,131,133,1355,434,1459,1621,1625,4389,1114,1118,1307,1653,1874,2117,2206,2829,2843,4095,4097,4616,4637,4639,4641,151,165,185,436,440,1377,1379,1381,1389,1431,1439,1537,1539,1541,1543,1545,1547,1553,1601,1603,1611,1461,1463,1467,1469,1485,1493,1495,1499,1501,1503,1505,1507,1509,1613,1615,1619,1623,1627,1711,1713,1715,2094,2098,2100,2102,2212,2214,2216,2218,2220,2012,2014,2016,2030,2032,2034,2036,2038,2040,2048,2050,2052,2062,2064,2068,2070,2072,2074,2076,4153,4167,4173,4175,4183,4205,4220,4228,4240,4274,4379,4383,4393,4401,4403,4419,4421,4445,4473,4489,4491,4493,4495,4497,4501,4503,4507,1349,1363,1367,1952,1954,1958,1960,1962,1964,1976,1978,1992,1996,2002,2010,2018,2020,2022,2024,2026,2028,2042,2044,2046,2054,2056,2058,2060,2066,2080,2088,4224,4250,4252,4407,4487,4499,4509,4511,4513,4515,1455,1549,1567,1489,1998,4086,4331,4505,1457,1477,1483,5171,2210,2222,4521,4541,4545,2237,2239);
#delete from psa_media where media_directory_id in (select media_directory_id from psa_media_directory where SITE_ID>2);
update psa_media_directory set site_id=2 where site_id>2;
update psa_media_directory set media_directory_parent_id=null where media_directory_id=1;
update psa_media_directory set media_directory_parent_id=1 where media_directory_id in (162,480);
update psa_media_directory set media_directory_label='MASTER' where media_directory_id =480;
update psa_media_directory set media_directory_label='TEST' where media_directory_id =162;

delete from psa_media where media_directory_id in (158,159,149,156,166,42,154,155);
delete from psa_media_directory where media_directory_id in (166,159,158,155);
delete from psa_media_directory where media_directory_id in (156);
delete from psa_media_directory where media_directory_id in (149,154);
delete from psa_media_directory where media_directory_id in (42);
#delete from psa_media_directory where media_directory_parent_id is not null and SITE_ID>2;
#delete from psa_media_directory where SITE_ID>2;
DELETE from psa_tag where SITE_ID > 2;

delete from psa_user_profile where profile_id in (select profile_id from psa_profile where SITE_ID>2);
delete from psa_user_profile where profile_id not in (select profile_id from psa_profile);
delete from psa_profile_directory where profile_id in (select profile_id from psa_profile where SITE_ID>2);
delete from psa_profile where SITE_ID>2;
delete from psa_user where user_login not in (select user_login from psa_user_profile);
delete from psa_user_role where user_login not in (select user_login from psa_user);
delete from psa_user_role where content_type_id in (select content_type_id from psa_content_type where CONTENT_TYPE_LABEL like '%(%');
DELETE from psa_directory_site where SITE_ID > 2;
DELETE from psa_comment where SITE_ID > 2;
DELETE from psa_content_type_site where content_type_id in (select content_type_id from psa_content_type where CONTENT_TYPE_LABEL like '%(%');
DELETE from psa_content_type_site where SITE_ID>2;
DELETE from psa_content_type where CONTENT_TYPE_LABEL like '%(%';


truncate table psa_page_zone_media;
truncate table psa_page_zone_content;
update psa_template_page set site_id=2;
update psa_template_page set site_id=5 where template_page_id<=288;
update psa_page_version set template_page_id=290 where template_page_id<=288;
delete from psa_page_zone_multi where zone_template_id in (select zone_template_id FROM psa_zone_template where template_page_id in (select template_page_id from psa_template_page where SITE_ID>2 or TEMPLATE_PAGE_LABEL = '-Sans nom-'));
DELETE FROM psa_zone_template where template_page_id in (select template_page_id from psa_template_page where SITE_ID>2 or TEMPLATE_PAGE_LABEL = '-Sans nom-');
DELETE FROM psa_template_page_area where template_page_id in (select template_page_id from psa_template_page where SITE_ID>2 or TEMPLATE_PAGE_LABEL = '-Sans nom-');
DELETE from psa_area where AREA_LABEL like '%Citroën%';
DELETE from psa_area where AREA_LABEL like '%Cardif%';
DELETE FROM psa_zone_template where template_page_id not in (select template_page_id from psa_template_page);
DELETE FROM psa_template_page_area where template_page_id not in (select template_page_id from psa_template_page);
DELETE FROM psa_template_page_area where template_page_id in (select template_page_id from psa_template_page where SITE_ID>2 or TEMPLATE_PAGE_LABEL = '-Sans nom-');
DELETE FROM psa_template_page_area where template_page_id not in (select template_page_id from psa_template_page);
DELETE FROM psa_template_page where SITE_ID>2 or TEMPLATE_PAGE_LABEL = '-Sans nom-';
DELETE from psa_template where TEMPLATE_LABEL like '%Citroën%';
DELETE from psa_template where TEMPLATE_LABEL like '%Cardif%';

truncate table psa_user_zone_template;
DELETE FROM psa_zone_description where zone_id in (select zone_id from psa_zone where Zone_LABEL like '%Citroën%');
DELETE from psa_zone where Zone_LABEL like '%Citroën%';
DELETE from psa_zone where Zone_LABEL like '%Cardif%';
DELETE FROM psa_zone_description where zone_id not in (select zone_id from psa_zone_template);
delete from psa_zone where zone_id not in (select zone_id from psa_zone_template);
DELETE FROM psa_zone_description where zone_id not in (select zone_id from psa_zone);
delete from psa_template_page_area where area_id not in (select area_id from psa_zone_template);
delete from psa_area where area_id not in (select area_id from psa_zone_template);
delete from psa_template_page_area where area_id not in (select area_id from psa_zone_template);
DELETE from psa_site_dns where SITE_ID > 2;
DELETE from psa_site_language where SITE_ID > 2;
DELETE from psa_site_parameter where SITE_ID > 2;
DELETE from psa_site_parameter_dns where SITE_ID > 2;
DELETE from psa_site_code where SITE_ID > 2;
DELETE from psa_site where SITE_ID > 2;


delete from psa_page_type where page_type_id >=9 and page_type_id not in (select page_type_id from psa_template_page);

update psa_site set site_label='Peugeot FR (Master)' where site_id=2;

delete from psa_template where template_id in (24,71,302);

delete from psa_navigation where page_id >2 and page_id <2931;
delete from psa_page_version_content where page_id >2 and page_id <2931;
delete from psa_page_multi_zone_content where page_id >2 and page_id <2931;
delete from psa_page_multi_zone_media where page_id >2 and page_id <2931;
delete from psa_page_multi_zone where page_id >2 and page_id <2931;
delete from psa_page_zone_content where page_id >2 and page_id <2931;
delete from psa_page_zone_media where page_id >2 and page_id <2931;
delete from psa_page_zone where page_id >2 and page_id <2931;
delete from psa_page_version_media where page_id >2 and page_id <2931;
delete from psa_page_version where page_id >2 and page_id <2931;