-- MySQL dump 10.13  Distrib 5.5.60, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: obsessed
-- ------------------------------------------------------
-- Server version	5.6.33-0ubuntu0.14.04.1

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
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reply_to_post_id` int(11) NOT NULL,
  `reply_to_comment_id` int(11) DEFAULT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `obsessions`
--

DROP TABLE IF EXISTS `obsessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `obsessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `obsessions`
--

LOCK TABLES `obsessions` WRITE;
/*!40000 ALTER TABLE `obsessions` DISABLE KEYS */;
INSERT INTO `obsessions` VALUES (1,'Side Projects','2018-06-30 00:00:00');
/*!40000 ALTER TABLE `obsessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `external_id` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source_type` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source_id` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `url` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thumbnail` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,'8uwztk','reddit','SideProject','A database of 40k+ investors to raise your seed round ?','I have worked developing a startup for a few years so far. We run a fintech company that requires quite a lot of capital and we always needed to rely on investors to support our business. When I started looking for investors, I found that finding angel investors and venture capitalists was extremely time consuming and hard. I would spend about 10 days to find 500 emails of VCs and Angels and manually put them one by one on my excel spreadsheet. After doing it for a while, I understood that there must be so many people out there who are having similar issues. So we created Investorhunt - a database of 40k+ investors which will save you hundreds of hours of research on AngelList, Crunchbase, and LinkedIn trying to find the right investors and their emails. Check us out and let us know what you think. ?  \n\n\n[https://investorhunt.co/](https://investorhunt.co/)  https://www.reddit.com/r/SideProject/comments/8uwztk/a_database_of_40k_investors_to_raise_your_seed/','https://www.reddit.com//r/SideProject/comments/8uwztk/a_database_of_40k_investors_to_raise_your_seed/','self','https://i.redditmedia.com/AgKU1_lxmQ1LsvUHYKIVnf70m0BqokIkKxX8u1_S9Nk.jpg?s=b4179ab9d98824dae514381e4f457e07',10,'2018-06-29 21:48:39'),(2,'8uwcf1','reddit','SideProject','Update: Changed my service to a job search engine? What do you think (got UK job atm)?','A few weeks ago, I posted a message about making a job board service based on skills. Although there was a problem for this, it was a massive problem. As going through this problem, I realised there are many job board services and people would have access the job boards itself to see current jobs. So, as there where many job board services, I thought of changing it to a job search engine. You simply add a job and location and it will search jobs from different job boards and display them. What do you think?\n\nPlease note: At the moment, it has UK jobs only, other areas will come soon but waiting for responses from industries. If your from America, then there are some (very minor), here is an example: [Munchjobs USA Example](https://www.munchjobs.com/jobs?search_txt=python&amp;location_txt=new+york%2Cunited+states)\n\n**Link:** [https://www.munchjobs.com](https://www.munchjobs.com) https://www.reddit.com/r/SideProject/comments/8uwcf1/update_changed_my_service_to_a_job_search_engine/','https://www.reddit.com//r/SideProject/comments/8uwcf1/update_changed_my_service_to_a_job_search_engine/','self','https://i.redditmedia.com/fOJH3IJFwamozG3RjfEQPNo0rx4t7a5-3izZVt6ljo8.jpg?s=ae00b7d841076e428f194e7333af1d8b',1,'2018-06-29 20:21:30'),(3,'8uvwqz','reddit','SideProject','Is there a market for this?','We have developed a website where you can check the background noise level for any address in the lower 48 states ([www.Ambient-Logic.com](http://www.ambient-logic.com/)). We\'ve done all of the back end work - JavaScript for the map, Node and Java for the server side, and I think we\'ve got a decent website setup. The question now is, will anyone care? We\'re hoping to monetize this be selling API access (we\'ve got JS widget and a WP plugin ready to go). What do you think? We\'d love any and all feedback! https://www.reddit.com/r/SideProject/comments/8uvwqz/is_there_a_market_for_this/','https://www.reddit.com//r/SideProject/comments/8uvwqz/is_there_a_market_for_this/','self','https://i.redditmedia.com/YkZuFpYZ1J9uqoR11M-64CuDGzrPdEi4XV_K9Ec7JXg.jpg?s=ff6472b57a313493b13f590951365699',3,'2018-06-29 19:27:09'),(4,'8uum2t','reddit','SideProject','Macrowave - Simple uptime monitoring for teams and individuals',' https://macrowave.co?ref=reddit','https://www.reddit.com//r/SideProject/comments/8uum2t/macrowave_simple_uptime_monitoring_for_teams_and/','https://b.thumbs.redditmedia.com/hnYNowmC7GtCGqmraRLYhkT0C-s2bB9O5f9Nd2Te-Ig.jpg','https://i.redditmedia.com/d0XMiincOuQx8TxrMmtZK_7A16Y5-bcP9xPQitk2FLw.jpg?s=a52cf3bf3374fd8e1854c35ec938e62b',1,'2018-06-29 16:45:26'),(5,'8usxmu','reddit','SideProject','We are launching a new product: daily newsletters about the art market.',' https://mailchi.mp/feralhorses.co.uk/daily-art-market-intake','https://www.reddit.com//r/SideProject/comments/8usxmu/we_are_launching_a_new_product_daily_newsletters/','https://b.thumbs.redditmedia.com/8m5yCkWXuvVI24PxAGVXiCSyP5MjvKwd-qdgQuXSDVI.jpg','https://i.redditmedia.com/6sO4FkN4ZDxzDCgTonZ_VEJv-yVGHFx1Agj6gBmWHKA.jpg?s=eff11f64c4c5cfcf2bd0ccaf07ea5a88',1,'2018-06-29 13:00:51'),(6,'8usxc1','reddit','SideProject','I need advice, I\'m planning to build an app that would once taken a picture of written lines of text make a to-do list automatically','Hey guys, I mainly manage my tasks via a written list in my notebook, I also keep a secondary to-do list on my smartphone. Usually I replicate the written tasks on my phone by adding manually to the to-do app, So as a workaround I am planning to make an app **that\'ll convert any written lines of text into manageable to-do list just by taking a picture of it. Illustrated below** \n\nhttps://i.redd.it/vectkiaytx611.png\n\nWhat do you guys think of it? Will it be useful for you guys? https://www.reddit.com/r/SideProject/comments/8usxc1/i_need_advice_im_planning_to_build_an_app_that/','https://www.reddit.com//r/SideProject/comments/8usxc1/i_need_advice_im_planning_to_build_an_app_that/','https://b.thumbs.redditmedia.com/nAk1dCYwRRCepdVHCIbD5QMjpSvDf1pqh2iOlAG04hU.jpg','https://i.redditmedia.com/6sO4FkN4ZDxzDCgTonZ_VEJv-yVGHFx1Agj6gBmWHKA.jpg?s=eff11f64c4c5cfcf2bd0ccaf07ea5a88',5,'2018-06-29 12:59:32'),(7,'8uox8r','reddit','SideProject','CSS shape-outside playground',' https://olivierforget.net/css-shape-outside/','https://www.reddit.com//r/SideProject/comments/8uox8r/css_shapeoutside_playground/','default','https://i.redditmedia.com/6sO4FkN4ZDxzDCgTonZ_VEJv-yVGHFx1Agj6gBmWHKA.jpg?s=eff11f64c4c5cfcf2bd0ccaf07ea5a88',1,'2018-06-29 00:43:14'),(8,'8uotwg','reddit','SideProject','CustomerDiscovery - We help discover your target market and validate your business model','Our latest service offering is geared toward helping developers with side projects take them to the next level.\n\nOur team will work closely with you to design and execute quick, effective experiments in order to quickly discover your perfect target customer, how to effectively reach them, and what aspects of your product they are most willing to pay for.\n\nin other words, we get you one step closer to product/market fit and one step closer to turning your project into a successful, profitable business.\n\nIf you\'re interested in checking it out, go to [https://www.elevationlab.io](https://www.elevationlab.io/).  Otherwise, I\'d love to hear your feedback! https://www.reddit.com/r/SideProject/comments/8uotwg/customerdiscovery_we_help_discover_your_target/','https://www.reddit.com//r/SideProject/comments/8uotwg/customerdiscovery_we_help_discover_your_target/','self','https://i.redditmedia.com/6sO4FkN4ZDxzDCgTonZ_VEJv-yVGHFx1Agj6gBmWHKA.jpg?s=eff11f64c4c5cfcf2bd0ccaf07ea5a88',5,'2018-06-29 00:28:29'),(9,'8ulx9y','reddit','SideProject','Built a Twitter bot. You define the topic and the bot grabs Tweets. You decide if the bot tweets this again on your account or not. Next step: build haptic buttons with Rasp Pi for „Yes“ / „No“',' https://youtu.be/xcfESlSx8o8','https://www.reddit.com//r/SideProject/comments/8ulx9y/built_a_twitter_bot_you_define_the_topic_and_the/','https://b.thumbs.redditmedia.com/JxOl6bwJLeYnyAEelKqtyRfZ-txsDVXE6e64TjHWn2M.jpg','https://i.redditmedia.com/Y_GGgzuqYT7L250n4InR62speGSnXiQotojeP8DGjrs.jpg?s=ef2ed10b5ea9ca217e0f842ce7a53c25',1,'2018-06-28 18:06:06'),(10,'8uk69h','reddit','SideProject','[WIP] Goaly - An app to track goals the Ray Dalio way.','I recently read Ray Dalio\'s Principles and fell in love the 5 step process he describes to solve any problem.\n\n5-Step Process\n\n1. Have **clear goals.**\n2. Identify and **don’t tolerate the problems** that stand in the way of your achieving those goals.\n3. Accurately diagnose the problems to get at their **root causes.**\n4. **Design plans** that will get you around them.\n5. Do what’s necessary to **push these designs through** to results.\n\nLike he describes here too - [https://youtu.be/B9XGUpQZY38](https://youtu.be/B9XGUpQZY38)\n\nI searched for an app around this method but there weren\'t any. I tried using Trello and plain pen paper but I believe this method deserves an app.\n\nHope this helps you in some way. I\'ll love any feedback.\n\nThe app\'s built on react-native\n\n[https://play.google.com/store/apps/details?id=in.goaly.androidapp](https://play.google.com/store/apps/details?id=in.goaly.androidapp) https://www.reddit.com/r/SideProject/comments/8uk69h/wip_goaly_an_app_to_track_goals_the_ray_dalio_way/','https://www.reddit.com//r/SideProject/comments/8uk69h/wip_goaly_an_app_to_track_goals_the_ray_dalio_way/','self','https://i.redditmedia.com/J_LDCDdto6vqmtKB7ot0w_354XWZa_rgrnVqxG6SHi4.jpg?s=8e675abfce40418fefd121854b6da9b6',5,'2018-06-28 14:57:52'),(11,'8ui7xt','reddit','SideProject','My first startup - View information about the top 100 cryptocurrencies and get personalized recommendations.',' https://cryptobroom.com','https://www.reddit.com//r/SideProject/comments/8ui7xt/my_first_startup_view_information_about_the_top/','https://b.thumbs.redditmedia.com/qjL5u4WfDVjdwWlxvpMaeGYArfmghruIupdYMAAnJpE.jpg','https://i.redditmedia.com/urd7G3C7fctXL2XkDCLv3AEfdbPZDHBHuTspERkpFbY.jpg?s=5facec8dbe71481cca427e76b5f8e3c6',0,'2018-06-28 10:54:41'),(12,'8ui3xi','reddit','SideProject','Researching people who are active outdoors - what we learned','We\'re building www.torrential.app as a side project. This is part of our user research. Hoping it will be useful to others!\n\nhttps://medium.com/torrential/researching-people-who-are-active-outdoors-torrential-app-d396212da012\n\nTLDR:\n- We surveyed 59 people in 48 hours\n- 46% (27) were High Activity users (&gt;4 sessions &gt;40 mins p/week)\n- The survey could have been more focused on the problem we want to solve\n- People report valuing intelligent weather alerts (validating our MVP)\n\nComments/crits welcome!\n\nIf you are active outdoors and have 5 minutes, PLEASE TAKE PART IN OUR NEXT SURVEY - https://goo.gl/forms/S1P9epm5t4XDyRgz1 https://www.reddit.com/r/SideProject/comments/8ui3xi/researching_people_who_are_active_outdoors_what/','https://www.reddit.com//r/SideProject/comments/8ui3xi/researching_people_who_are_active_outdoors_what/','self','https://i.redditmedia.com/PC-NW1AbbmzNicSuUjHHN0JCfJQfPoRjfbKp6ED6Vqw.jpg?s=232c2ed7a529c4f6a48ac7882c45cf81',2,'2018-06-28 10:38:41'),(13,'8ufvpz','reddit','SideProject','Analytics for Google Payments - business metrics for Chrome Extensions, Android apps, etc',' https://analytics.simon.codes','https://www.reddit.com//r/SideProject/comments/8ufvpz/analytics_for_google_payments_business_metrics/','https://b.thumbs.redditmedia.com/avtLyEv7tFqWfzPGwAS2GXIrVqbUIxxKIIB0JsmosWQ.jpg','https://i.redditmedia.com/Ni1Ilrn8dt9k9KQbtleZTkqgM6dmvY820kOoKRDQ7MM.jpg?s=29131260e06b0c2290b2bb376521d6c6',1,'2018-06-28 03:10:16'),(14,'8ub270','reddit','SideProject','Side Project: Find the best tattoo artists in your city',' https://outline.wtf','https://www.reddit.com//r/SideProject/comments/8ub270/side_project_find_the_best_tattoo_artists_in_your/','default','https://i.redditmedia.com/Ni1Ilrn8dt9k9KQbtleZTkqgM6dmvY820kOoKRDQ7MM.jpg?s=29131260e06b0c2290b2bb376521d6c6',4,'2018-06-27 16:32:10'),(15,'8ua93x','reddit','SideProject','Characters Live - Real People Inspire Writers','https://www.characterslive.com https://www.reddit.com/r/SideProject/comments/8ua93x/characters_live_real_people_inspire_writers/','https://www.reddit.com//r/SideProject/comments/8ua93x/characters_live_real_people_inspire_writers/','self','https://i.redditmedia.com/Ni1Ilrn8dt9k9KQbtleZTkqgM6dmvY820kOoKRDQ7MM.jpg?s=29131260e06b0c2290b2bb376521d6c6',1,'2018-06-27 14:54:22'),(16,'8u9qwr','reddit','SideProject','PurchMockups - Apparel Mock-up Generator / A PlaceIt Alterative','A buddy of mine needed a simple mockup service for his amazon designs, all templates were created using Amazon\'s standards (4500 x 5400)  https://www.reddit.com/r/SideProject/comments/8u9qwr/purchmockups_apparel_mockup_generator_a_placeit/','https://www.reddit.com//r/SideProject/comments/8u9qwr/purchmockups_apparel_mockup_generator_a_placeit/','self','https://i.redditmedia.com/Ni1Ilrn8dt9k9KQbtleZTkqgM6dmvY820kOoKRDQ7MM.jpg?s=29131260e06b0c2290b2bb376521d6c6',1,'2018-06-27 13:47:54'),(17,'8u93zm','reddit','SideProject','How to Make a Curation Website in 20 Minutes',' https://www.newco.app/members/how-to-build-a-curated-resources-website','https://www.reddit.com//r/SideProject/comments/8u93zm/how_to_make_a_curation_website_in_20_minutes/','default','https://i.redditmedia.com/Ni1Ilrn8dt9k9KQbtleZTkqgM6dmvY820kOoKRDQ7MM.jpg?s=29131260e06b0c2290b2bb376521d6c6',4,'2018-06-27 12:14:27'),(18,'8u8x1l','reddit','SideProject','I built a chrome extension to track my habits','Our life’s outcome depend on our habits. It’s astonishing how small things compound over time: \n\n* Reading 10 pages a day = 3600 pages a year = 14 books a year \n* Avoiding drinking a coke each day = 54k calories we skipped on a year \n* Writing 100 words per day = 36 blog posts per year \n\nI’ve always had trouble sticking to new habits. So I decided to build a chrome extension to track my progress and keep me on track.\n\n  \nIt was designed for me. So far I’ve made a ton of progress on my meditation, reading and working out habits.    \nAfter seeing my progress I decided to improve it so it can be used by anyone.\n\n  \nYou can try it on this website: [https://myhabits.co/](https://myhabits.co/) . If you like it you can follow the link at the bottom to install it on Chrome.\n\n  \nOne of my dreams is to become an entrepreneur. This is one of my first attempts.\n\n  \nYou can track up to 3 habits for free, if you want to track unlimited habits it only costs 1.25$ per month. This will help me keep the project alive, pay the servers, continue adding features and buy a cup of coffee every now and then.\n\n  \nI’d love to hear your feedback.  https://www.reddit.com/r/SideProject/comments/8u8x1l/i_built_a_chrome_extension_to_track_my_habits/','https://www.reddit.com//r/SideProject/comments/8u8x1l/i_built_a_chrome_extension_to_track_my_habits/','self','https://i.redditmedia.com/HigArE012Pw0ehB4s9m54Rku6RqoWn2IZs8vixfEfcM.jpg?s=ff7ce3e4c9fbc200d9add3a5169eec1d',15,'2018-06-27 11:43:23'),(19,'8u7saa','reddit','SideProject','ishmooz : Classifieds for Slack teams',' https://www.ishmooz.com','https://www.reddit.com//r/SideProject/comments/8u7saa/ishmooz_classifieds_for_slack_teams/','default','https://i.redditmedia.com/HigArE012Pw0ehB4s9m54Rku6RqoWn2IZs8vixfEfcM.jpg?s=ff7ce3e4c9fbc200d9add3a5169eec1d',1,'2018-06-27 07:58:53'),(20,'8u6y2y','reddit','SideProject','Orions Comet',' http://www.orionscomet.com/','https://www.reddit.com//r/SideProject/comments/8u6y2y/orions_comet/','https://b.thumbs.redditmedia.com/c2fdKYDuL_2StYjEL8a8o3JXCGcvt63pG78oZ6YYkRk.jpg','https://i.redditmedia.com/F6AGihc6sjMmnVLm-XWIe0jcP2vPZpBwjOqH2PhZX-c.jpg?s=56a677929f9af56fa137ae07c36cfd15',0,'2018-06-27 05:14:35'),(21,'8u6lqi','reddit','SideProject','Mugatunes',' http://www.republic.co/mugatunes','https://www.reddit.com//r/SideProject/comments/8u6lqi/mugatunes/','https://b.thumbs.redditmedia.com/iwWccngFdRlW8lFkdwcPq-bebxY-td5_0H9KnqQ6BmU.jpg','https://i.redditmedia.com/OhPSAD5EOezMBytxVlmbVql-VaLki3brbskdSj2j0E0.jpg?s=9e473aba26236bd17626ec955405ca30',2,'2018-06-27 04:14:37'),(22,'8u5tns','reddit','SideProject','Notifee - The free notification service.','Hi everybody,\n\nhttps://nt.codfee.xyz/\n\nHere is my side project which I made at the free time, this is a service that provides to us a way to `notify the message` to your user (your blog, your side-project, internal app,...). \n\nLike me, I have some websites such as my blog, some side-projects... which I want to send a message to notify all of the users but I very lazy to implement one by one. As you known the site project should be used to resolve the problem for the author first and then surely is sharing. :D\n\nIf you have some problems like me and you are interested in this one, don\'t hesitate to comment below to me.\n\nFeatures:\n- Send to your email the URL to go to dashboard\n- Dashboard to manage the settings\n- Enable/disable notification\n- See the number `view watched`\n- No sign-up\n- Free\n___\n\n**Secure part**: Because this service need you `inject the embed script` and set your `key` in your website like as Disqus, google analytics,... but don\'t worry because it just call one request to server to get the `notification content` (which you can check at dev tools or something like as...)\n\nThanks. https://www.reddit.com/r/SideProject/comments/8u5tns/notifee_the_free_notification_service/','https://www.reddit.com//r/SideProject/comments/8u5tns/notifee_the_free_notification_service/','self','https://i.redditmedia.com/OhPSAD5EOezMBytxVlmbVql-VaLki3brbskdSj2j0E0.jpg?s=9e473aba26236bd17626ec955405ca30',1,'2018-06-27 02:09:18'),(23,'8u5ryz','reddit','SideProject','Web2Desk – Your favorite websites to desktop apps in one click',' http://desktop.appmaker.xyz','https://www.reddit.com//r/SideProject/comments/8u5ryz/web2desk_your_favorite_websites_to_desktop_apps/','default','https://i.redditmedia.com/OhPSAD5EOezMBytxVlmbVql-VaLki3brbskdSj2j0E0.jpg?s=9e473aba26236bd17626ec955405ca30',10,'2018-06-27 02:02:18'),(24,'8u33kx','reddit','SideProject','And Be Honest - Ask a question. Get anonymous answers from your friends.',' http://www.andbehonest.com/','https://www.reddit.com//r/SideProject/comments/8u33kx/and_be_honest_ask_a_question_get_anonymous/','default','https://i.redditmedia.com/OhPSAD5EOezMBytxVlmbVql-VaLki3brbskdSj2j0E0.jpg?s=9e473aba26236bd17626ec955405ca30',9,'2018-06-26 19:52:42'),(25,'8u13eb','reddit','SideProject','PenguinProxy - A Peer to Peer VPN','Hey Reddit, after learning about GDPR I made a peer to peer VPN service. This lets you trade your internet with users from another country. What do you think? Check it out here [PenguinProxy](https://penguinproxy.com). https://www.reddit.com/r/SideProject/comments/8u13eb/penguinproxy_a_peer_to_peer_vpn/','https://www.reddit.com//r/SideProject/comments/8u13eb/penguinproxy_a_peer_to_peer_vpn/','self','https://i.redditmedia.com/OhPSAD5EOezMBytxVlmbVql-VaLki3brbskdSj2j0E0.jpg?s=9e473aba26236bd17626ec955405ca30',5,'2018-06-26 15:50:15');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sources`
--

DROP TABLE IF EXISTS `sources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sources`
--

LOCK TABLES `sources` WRITE;
/*!40000 ALTER TABLE `sources` DISABLE KEYS */;
INSERT INTO `sources` VALUES (1,'reddit','SideProject','2018-06-30 00:00:00');
/*!40000 ALTER TABLE `sources` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sources_obsessions`
--

DROP TABLE IF EXISTS `sources_obsessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sources_obsessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `source_id` int(11) NOT NULL,
  `obsession_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sources_obsessions`
--

LOCK TABLES `sources_obsessions` WRITE;
/*!40000 ALTER TABLE `sources_obsessions` DISABLE KEYS */;
INSERT INTO `sources_obsessions` VALUES (1,1,1);
/*!40000 ALTER TABLE `sources_obsessions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-06-30 12:08:46
