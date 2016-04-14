-- MySQL dump 10.13  Distrib 5.6.23, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: psa-ndp
-- ------------------------------------------------------
-- Server version	5.6.23-1~dotdeb.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Temporary view structure for view `psa_page_areas`
--

DROP TABLE IF EXISTS `psa_page_areas`;
/*!50001 DROP VIEW IF EXISTS `psa_page_areas`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `psa_page_areas` AS SELECT 
 1 AS `AREA_UID`,
 1 AS `PAGE_ID`,
 1 AS `PAGE_VERSION`,
 1 AS `LANGUE_ID`,
 1 AS `TEMPLATE_PAGE_ID`,
 1 AS `AREA_ID`,
 1 AS `DYNAMIC_AREA`,
 1 AS `TEMPLATE_PAGE_AREA_ORDER`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `psa_page_areas_blocks`
--

DROP TABLE IF EXISTS `psa_page_areas_blocks`;
/*!50001 DROP VIEW IF EXISTS `psa_page_areas_blocks`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `psa_page_areas_blocks` AS SELECT 
 1 AS `AREA_UID`,
 1 AS `BLOCK_UID`,
 1 AS `PERMANENT_ID`,
 1 AS `PAGE_ID`,
 1 AS `PAGE_VERSION`,
 1 AS `LANGUE_ID`,
 1 AS `TEMPLATE_PAGE_ID`,
 1 AS `AREA_ID`,
 1 AS `DYNAMIC_AREA`,
 1 AS `ZONE_ID`,
 1 AS `BLOCK_PAGE_DATA_UID`,
 1 AS `MULTI_ZONE_UID`,
 1 AS `TEMPLATE_PAGE_AREA_ORDER`,
 1 AS `ZONE_ORDER`,
 1 AS `ZONE_LABEL`,
 1 AS `SITE_ID`*/;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `psa_page_areas`
--

/*!50001 DROP VIEW IF EXISTS `psa_page_areas`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`psa-ndp`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `psa_page_areas` AS (select concat_ws('.',`pv`.`PAGE_ID`,`pv`.`PAGE_VERSION`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`tpa`.`AREA_ID`) AS `AREA_UID`,`pv`.`PAGE_ID` AS `PAGE_ID`,`pv`.`PAGE_VERSION` AS `PAGE_VERSION`,`pv`.`LANGUE_ID` AS `LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID` AS `TEMPLATE_PAGE_ID`,`tpa`.`AREA_ID` AS `AREA_ID`,`a`.`AREA_DROPPABLE` AS `DYNAMIC_AREA`,`tpa`.`TEMPLATE_PAGE_AREA_ORDER` AS `TEMPLATE_PAGE_AREA_ORDER` from ((`psa_page_version` `pv` join `psa_template_page_area` `tpa` on((`tpa`.`TEMPLATE_PAGE_ID` = `pv`.`TEMPLATE_PAGE_ID`))) join `psa_area` `a` on((`tpa`.`AREA_ID` = `a`.`AREA_ID`))) where isnull(`a`.`AREA_DROPPABLE`)) union all (select concat_ws('.',`pv`.`PAGE_ID`,`pv`.`PAGE_VERSION`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`a`.`AREA_ID`) AS `AREA_UID`,`pv`.`PAGE_ID` AS `PAGE_ID`,`pv`.`PAGE_VERSION` AS `PAGE_VERSION`,`pv`.`LANGUE_ID` AS `LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID` AS `TEMPLATE_PAGE_ID`,`a`.`AREA_ID` AS `AREA_ID`,`a`.`AREA_DROPPABLE` AS `DYNAMIC_AREA`,`tpa`.`TEMPLATE_PAGE_AREA_ORDER` AS `TEMPLATE_PAGE_AREA_ORDER` from ((`psa_page_version` `pv` join `psa_template_page_area` `tpa` on((`tpa`.`TEMPLATE_PAGE_ID` = `pv`.`TEMPLATE_PAGE_ID`))) join `psa_area` `a` on((`tpa`.`AREA_ID` = `a`.`AREA_ID`))) where (`a`.`AREA_DROPPABLE` = 1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `psa_page_areas_blocks`
--

/*!50001 DROP VIEW IF EXISTS `psa_page_areas_blocks`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`psa-ndp`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `psa_page_areas_blocks` AS select concat_ws('.',`pv`.`PAGE_ID`,`pv`.`PAGE_VERSION`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`zt`.`AREA_ID`) AS `AREA_UID`,concat_ws('.',`pv`.`PAGE_ID`,`pv`.`PAGE_VERSION`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`zt`.`AREA_ID`,`zt`.`ZONE_ID`,`zt`.`ZONE_TEMPLATE_ORDER`) AS `BLOCK_UID`,concat_ws('.',`pv`.`PAGE_ID`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`zt`.`AREA_ID`,`zt`.`ZONE_ID`,`zt`.`ZONE_TEMPLATE_ORDER`) AS `PERMANENT_ID`,`pv`.`PAGE_ID` AS `PAGE_ID`,`pv`.`PAGE_VERSION` AS `PAGE_VERSION`,`pv`.`LANGUE_ID` AS `LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID` AS `TEMPLATE_PAGE_ID`,`zt`.`AREA_ID` AS `AREA_ID`,`a`.`AREA_DROPPABLE` AS `DYNAMIC_AREA`,`zt`.`ZONE_ID` AS `ZONE_ID`,`zt`.`ZONE_TEMPLATE_ID` AS `BLOCK_PAGE_DATA_UID`,NULL AS `MULTI_ZONE_UID`,`tpa`.`TEMPLATE_PAGE_AREA_ORDER` AS `TEMPLATE_PAGE_AREA_ORDER`,`zt`.`ZONE_TEMPLATE_ORDER` AS `ZONE_ORDER`,`z`.`ZONE_LABEL` AS `ZONE_LABEL`,`p`.`SITE_ID` AS `SITE_ID` from (((((`psa_page_version` `pv` join `psa_template_page_area` `tpa` on((`tpa`.`TEMPLATE_PAGE_ID` = `pv`.`TEMPLATE_PAGE_ID`))) join `psa_zone_template` `zt` on(((`tpa`.`TEMPLATE_PAGE_ID` = `zt`.`TEMPLATE_PAGE_ID`) and (`tpa`.`AREA_ID` = `zt`.`AREA_ID`)))) join `psa_area` `a` on((`tpa`.`AREA_ID` = `a`.`AREA_ID`))) join `psa_zone` `z` on((`z`.`ZONE_ID` = `zt`.`ZONE_ID`))) join `psa_page` `p` on((`p`.`PAGE_ID` = `pv`.`PAGE_ID`))) where isnull(`a`.`AREA_DROPPABLE`) union all (select concat_ws('.',`pv`.`PAGE_ID`,`pv`.`PAGE_VERSION`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`a`.`AREA_ID`) AS `AREA_UID`,concat_ws('.',`pv`.`PAGE_ID`,`pv`.`PAGE_VERSION`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`a`.`AREA_ID`,`pmz`.`ZONE_ID`,`pmz`.`ZONE_ORDER`) AS `BLOCK_UID`,concat_ws('.',`pv`.`PAGE_ID`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`a`.`AREA_ID`,`pmz`.`ZONE_ID`,`pmz`.`UID`) AS `PERMANENT_ID`,`pv`.`PAGE_ID` AS `PAGE_ID`,`pv`.`PAGE_VERSION` AS `PAGE_VERSION`,`pv`.`LANGUE_ID` AS `LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID` AS `TEMPLATE_PAGE_ID`,`a`.`AREA_ID` AS `AREA_ID`,`a`.`AREA_DROPPABLE` AS `DYNAMIC_AREA`,`pmz`.`ZONE_ID` AS `ZONE_ID`,NULL AS `BLOCK_PAGE_DATA_UID`,`pmz`.`UID` AS `MULTI_ZONE_UID`,`tpa`.`TEMPLATE_PAGE_AREA_ORDER` AS `TEMPLATE_PAGE_AREA_ORDER`,`pmz`.`ZONE_ORDER` AS `ZONE_ORDER`,`z`.`ZONE_LABEL` AS `ZONE_LABEL`,`p`.`SITE_ID` AS `SITE_ID` from (((((`psa_page_version` `pv` join `psa_template_page_area` `tpa` on((`tpa`.`TEMPLATE_PAGE_ID` = `pv`.`TEMPLATE_PAGE_ID`))) join `psa_area` `a` on((`tpa`.`AREA_ID` = `a`.`AREA_ID`))) join `psa_page_multi_zone` `pmz` on(((`pmz`.`PAGE_ID` = `pv`.`PAGE_ID`) and (`pmz`.`LANGUE_ID` = `pv`.`LANGUE_ID`) and (`pmz`.`PAGE_VERSION` = `pv`.`PAGE_VERSION`) and (`tpa`.`AREA_ID` = `pmz`.`AREA_ID`)))) join `psa_zone` `z` on((`z`.`ZONE_ID` = `pmz`.`ZONE_ID`))) join `psa_page` `p` on((`p`.`PAGE_ID` = `pv`.`PAGE_ID`))) where (`a`.`AREA_DROPPABLE` = 1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-10-05 14:51:37
