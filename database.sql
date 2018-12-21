-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2017 at 12:24 PM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `naija_helpers`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_details`
--

CREATE TABLE `account_details` (
  `id` int(11) NOT NULL,
  `login_id` int(11) NOT NULL,
  `account_number` varchar(100) NOT NULL,
  `account_name` varchar(100) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `branch` varchar(100) NOT NULL,
  `plan` varchar(100) NOT NULL,
  `bitcoin_id` varchar(225) NOT NULL,
  `date_added` datetime NOT NULL,
  `last_updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account_details`
--

INSERT INTO `account_details` (`id`, `login_id`, `account_number`, `account_name`, `bank_name`, `branch`, `plan`, `bitcoin_id`, `date_added`, `last_updated`) VALUES
(3, 1, '0987643200', 'Harrison Oziegbe', 'First Bank Plc', 'BitCoin@ID', '', 'myjustgateerererreer', '2017-01-27 15:02:11', '2017-03-30 22:26:40'),
(4, 2, '0987654321', 'Harrison Oziegbe', 'First Bank', '', '', '', '2017-01-30 12:23:50', '0000-00-00 00:00:00'),
(6, 9, '09875432', 'dgfgf', 'First Bank Plc', 'Nigeria', 'basic', 'bitcoin address', '2017-03-04 06:07:51', '2017-03-17 13:51:20'),
(7, 3, '0909090988', 'Harrison Oziegbe', 'First Bank Plc', '', '', 'dhzdhtrfjtyku', '2017-03-08 13:36:43', '2017-03-08 13:36:51'),
(8, 13, '878654567', 'my Name ', 'GTBank', '', '', '', '2017-03-21 01:05:47', '0000-00-00 00:00:00'),
(9, 14, '00006545553346666', 'Happy John', 'Gtbank', '', '', 'bitcoin address', '2017-04-11 13:39:48', '2017-04-11 13:40:14'),
(10, 11, '656566556556', 'my Name ', 'GTBank', '', '', 'sgdfsgfdsgfghfhdhyjtyjtuukiru', '2017-04-14 23:56:21', '2017-04-14 23:56:36');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `role` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `photo` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `ip` int(11) NOT NULL,
  `last_login_ip` int(11) NOT NULL,
  `signup_date` datetime NOT NULL,
  `last_login` datetime NOT NULL,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `role`, `username`, `password`, `email`, `first_name`, `last_name`, `photo`, `status`, `ip`, `last_login_ip`, `signup_date`, `last_login`, `updated_date`) VALUES
(1, 'Administrator', 'admin', '$2y$10$iayzsI48/ddP.mAyVpGh6O4Pzx0mNFa0DnQ0HRxcXcA71HRSMxc3.', 'administrator@yoursite.com', 'MyfirestName', 'MyLastName', '', 'Active', 1, 197211, '2016-03-20 00:00:00', '2016-12-25 15:39:50', '2017-03-12 10:52:59'),
(11, 'Accounting', 'lady_b2021', '$2y$10$eC3nMhrjW/zpN1bVXnMi5eK167AM1MZ.oiWBRNv/zgpmCy.8I16qG', 'ozih@rocketmail.com', 'Citadelpayz', 'lady_b2021', '', 'Active', 0, 0, '2017-03-31 11:53:49', '0000-00-00 00:00:00', '2017-03-31 10:53:49');

-- --------------------------------------------------------

--
-- Table structure for table `admin_logged_in_users`
--

CREATE TABLE `admin_logged_in_users` (
  `id` int(11) NOT NULL,
  `admin` varchar(100) NOT NULL,
  `logged_in_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_logged_in_users`
--

INSERT INTO `admin_logged_in_users` (`id`, `admin`, `logged_in_time`) VALUES
(14, 'ann_naija', '2017-02-04 19:47:51'),
(15, 'ann_naija', '2017-02-08 13:29:49'),
(16, 'ann_naija', '2017-02-11 18:20:20'),
(17, 'ann_naija', '2017-02-13 09:49:07'),
(18, 'ann_naija', '2017-02-19 18:45:51'),
(19, 'ann_naija', '2017-02-21 13:22:36'),
(20, 'ann_naija', '2017-02-24 11:23:34'),
(21, 'ann_naija', '2017-03-02 10:21:44'),
(22, 'ann_naija', '2017-03-03 14:54:40'),
(23, 'ann_naija', '2017-03-03 22:42:56'),
(24, 'ann_naija', '2017-03-04 00:57:31'),
(25, 'ann_naija', '2017-03-05 22:17:27'),
(26, 'ann_naija', '2017-03-07 23:32:54'),
(27, 'ann_naija', '2017-03-08 11:28:21'),
(28, 'ann_naija', '2017-03-10 18:58:35'),
(29, 'ann_naija', '2017-03-12 09:57:50'),
(30, 'admin', '2017-03-12 10:53:36'),
(31, 'admin', '2017-03-17 18:50:23'),
(32, 'admin', '2017-03-18 13:59:03'),
(33, 'admin', '2017-03-18 22:48:12'),
(34, 'admin', '2017-03-19 14:31:53'),
(35, 'admin', '2017-03-19 16:24:38'),
(36, 'admin', '2017-03-20 23:00:33'),
(37, 'admin', '2017-03-20 23:09:54'),
(38, 'admin', '2017-03-31 09:25:22'),
(39, 'admin', '2017-04-05 11:53:25'),
(40, 'admin', '2017-04-08 13:55:29'),
(41, 'admin', '2017-04-08 16:05:06'),
(42, 'admin', '2017-04-14 05:49:54'),
(43, 'admin', '2017-04-14 20:05:20'),
(44, 'admin', '2017-04-14 20:20:23'),
(45, 'admin', '2017-04-25 21:50:49'),
(46, 'admin', '2017-04-28 10:55:03'),
(47, 'admin', '2017-04-29 13:07:26');

-- --------------------------------------------------------

--
-- Table structure for table `admin_login_activity`
--

CREATE TABLE `admin_login_activity` (
  `id` int(11) NOT NULL,
  `admin` varchar(100) NOT NULL,
  `ip` varchar(100) NOT NULL,
  `last_access` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_login_activity`
--

INSERT INTO `admin_login_activity` (`id`, `admin`, `ip`, `last_access`) VALUES
(14, 'ann_naija', '::1', '2017-02-04 19:47:51'),
(15, 'ann_naija', '::1', '2017-02-08 13:29:49'),
(16, 'ann_naija', '::1', '2017-02-11 18:20:19'),
(17, 'ann_naija', '127.0.0.1', '2017-02-13 09:49:07'),
(18, 'ann_naija', '::1', '2017-02-19 18:45:51'),
(19, 'ann_naija', '::1', '2017-02-21 13:22:36'),
(20, 'ann_naija', '::1', '2017-02-24 11:23:34'),
(21, 'ann_naija', '::1', '2017-03-02 10:21:44'),
(22, 'ann_naija', '::1', '2017-03-03 14:54:40'),
(23, 'ann_naija', '::1', '2017-03-03 22:42:56'),
(24, 'ann_naija', '::1', '2017-03-04 00:57:31'),
(25, 'ann_naija', '::1', '2017-03-05 22:17:27'),
(26, 'ann_naija', '::1', '2017-03-07 23:32:54'),
(27, 'ann_naija', '::1', '2017-03-08 11:28:21'),
(28, 'ann_naija', '::1', '2017-03-10 18:58:35'),
(29, 'ann_naija', '::1', '2017-03-12 09:57:49'),
(30, 'admin', '::1', '2017-03-12 10:53:36'),
(31, 'admin', '::1', '2017-03-17 18:50:23'),
(32, 'admin', '::1', '2017-03-18 13:59:03'),
(33, 'admin', '::1', '2017-03-18 22:48:12'),
(34, 'admin', '::1', '2017-03-19 14:31:53'),
(35, 'admin', '::1', '2017-03-19 16:24:38'),
(36, 'admin', '::1', '2017-03-20 23:00:33'),
(37, 'admin', '::1', '2017-03-20 23:09:54'),
(38, 'admin', '::1', '2017-03-31 09:25:22'),
(39, 'admin', '::1', '2017-04-05 11:53:25'),
(40, 'admin', '::1', '2017-04-08 13:55:29'),
(41, 'admin', '::1', '2017-04-08 16:05:05'),
(42, 'admin', '::1', '2017-04-14 05:49:54'),
(43, 'admin', '::1', '2017-04-14 20:05:20'),
(44, 'admin', '::1', '2017-04-14 20:20:23'),
(45, 'admin', '::1', '2017-04-25 21:50:48'),
(46, 'admin', '::1', '2017-04-28 10:55:02'),
(47, 'admin', '::1', '2017-04-29 13:07:25');

-- --------------------------------------------------------

--
-- Table structure for table `admin_notes`
--

CREATE TABLE `admin_notes` (
  `id` int(11) NOT NULL,
  `admin` varchar(100) NOT NULL,
  `note` text NOT NULL,
  `note_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `admin_notice`
--

CREATE TABLE `admin_notice` (
  `id` int(11) NOT NULL,
  `note` text NOT NULL,
  `last_update` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_notice`
--

INSERT INTO `admin_notice` (`id`, `note`, `last_update`) VALUES
(1, 'Hello Participants, \r\n\r\nDO NOT MAKE A PLEDGE IF YOU DON''T HAVE MONEY READILY AVAILABLE !!!\r\n\r\nPLEASE NOTE THAT ALL PH REMAIN ''PENDING'' UNTIL THEY ARE CONFIRMED...\r\n\r\nYOU WOULD BE REQUIRED TO PH WITHIN 48 HOURS OF PLEDGING (once active gh begins) AND YOUR 30 DAYS START COUNTING FROM THE MOMENT YOUR DONATION GETS CONFIRMED . YOU IMMEDIATELY GH UPON MATURITY\r\n\r\n**** ACTIVE GH STARTS ON THE 20TH OF APRIL ****\r\n\r\nGC is based on trust and actions of dishonour must be dealt with immediately to avoid corrosion of the system, we will ruthlessly deal with cases of fake proof of payment attachments by enforcing the following penalties. \r\n\r\n1. Once found guilty of uploading a fake POP, a total of 25% of your active referrals will be permanently removed from your account (this action will be immediate and CANNOT be reversed).\r\n\r\n2. Your next Provide Help will have a maturity rate of 60days.\r\n\r\n3. All pending referral bonuses in your account will be cancelled\r\n\r\n4. If you default for the third time, your account will be blocked and deleted from the system (no reactivation)\r\n\r\n5. If there is need for time extension; you are strongly advised to contact your match recipient for time extension.\r\n\r\n--\r\nBy clicking on "I AGREE" below, you are signifying that you have read,\r\nand accepted the above listed terms and conditions.', '2017-04-14 21:32:45');

-- --------------------------------------------------------

--
-- Table structure for table `contents`
--

CREATE TABLE `contents` (
  `c_id` int(11) NOT NULL,
  `privacy` longtext NOT NULL,
  `legality` longtext NOT NULL,
  `how_it_works` longtext NOT NULL,
  `middle_left_img` varchar(100) NOT NULL,
  `middle_right` text NOT NULL,
  `home_bottom_left` longtext NOT NULL,
  `home_bottom_right` longtext NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contents`
--

INSERT INTO `contents` (`c_id`, `privacy`, `legality`, `how_it_works`, `middle_left_img`, `middle_right`, `home_bottom_left`, `home_bottom_right`, `last_updated`) VALUES
(1, '<h3>Privacy Policy</h3>\r\nYoursiteName respects the privacy of all its users and is committed to guard it in all respects. To find out how user information collected at the website is protected, please read the following private policy document. Please note that YoursiteName can make changes to this private policy from time to time. Keep checking this page to know more about the updated private policies. Personal Information\r\nWe may collect the following information from our users: Name\r\nContact Information including email address\r\nPhoto\r\nDemographic Information such as postcode, preferences and interests Other information relevant to customer surveys and/or offers\r\n\r\n<h3>What We Do With The Information Collected</h3>\r\nThe information collected at the website is to present our valuable customers with better services and in particular for the following reasons:\r\nRecord Keeping for Internal Use.\r\nImprove our existing products and services.\r\nSend promotional emails periodically about new products, special offers or other information which users may find interesting.\r\n\r\n<h3>Security</h3>\r\nTo make the process of collecting information secure, suitable physical and electronic procedures have been integrated with the website. Your YoursiteName Office Account Security\r\nYour Account/Bitcoin Address CANNOT be changed once entered the first time, this security feature will make it impossible for anyone in possession of your password to divert your funds when its due to request for help. Keeping you 100% safe. If at anytime you wish to change the account/bitcoin details, you contact Support with a tangible reason and we will call to ensure we are speaking with the account owner.\r\n\r\n<h3>Modifying/Deleting Personal Information</h3>\r\nCustomers can modify or delete their personal information only by sending an email to support@yoursite.com\r\n', '<h3>LEGALITY</h3>\r\nYoursiteName is a people community, which help each other for free, and absolutely consciously, they transfer money directly to each other .i.e. peer-to-peer, from one bank account to another, without any conditions, guarantees and promises. Just only because they want to do that. Because it is a good deal to help each other. It means the Mutual benefit fund as it is. There is no formal organization, no legal person in YoursiteName . And of course there is no central bank account, no other activity in any form. Neither close, nor open. There is nothing! There are only millions and millions of simple participants, simple private persons all over the world. And nothing more.\r\n\r\nAnd of course there is no special registrations, permission and licenses in here. (Permissions and licenses for what and for what reason? For money transfer from one man to another? And whom this licenses and permissions should be given?. Thus, YoursiteName is absolutely legal and does not break any laws. Because there are only money transfers between private persons and nothing more.\r\n\r\nIt is impossible to forbid YoursiteName . The same way it is impossible to forbid people from managing their own money the way they like. Thus they need to forbid the whole private property institution. So everything is absolutely clear with the lawfulness. There are no violations and they won''t be! Let''s now talk about the other. About honesty. It is important to underline, that YoursiteName doesn''t cheat or abuse anyone. In other words it is not a cheat or fraud in any case! Everyone is informed about the possible and impossible risks fully and from the very beginning (And everyone put a mark during the registration, confirming that he was informed about the warning.)\r\n\r\nNOTE: This Platform has been designed to last forever, with the innovative features implemented to ensure that Participants remain active. All inactive accounts are BLOCKED if after 3days of receiving Help a Participant didnâ€™t place a New Pledge to Provide Help. We have done our Part to give you the Best Platform in the World; itâ€™s in your hands to keep it growing. Participate! Welcome to the System! Long Live YoursiteName ! â€“ Changing Lives! Changing Nations!! Changing the World!!!\r\n', '<h3>HOW DOES IT WORK</h3>\r\nHow does it work technically? You declare the willingness to Give Help (click on "Provide Help"), type in the amount you wish to Provide as Help either in your Local Currency on in Bitcoin after which your account will be rewarded with One Time Registration Bonus (depending on amount of Help Provided). Help provided will start growing from the moment it was entered online at the rate of 30% for Local Currency or 50% for Bitcoin in 30 days, while you remain with your money till you are paired with who you will donate directly to. In YoursiteName , you will be able to see picture of who you are donating to with their Country. Your Yield amount and date shows you Amount you can request on the 30th day and the date of Maturity. Say you have announced willingness to assist with $ 100 in Bitcoin, they will immediately start growing! By 50% in 30days, this $100 will become $150 in Bitcoin, if you declare willingness to give 1,000 in your Local currency (30% growth) in 30 Days it will become 1,300\r\n\r\nAccordingly, you will be able to request for Help of $ 150 in Bitcoin or 1,300 in your Local currency. Participants who fail to upload their Picture will earn 30% of their Provided Help in Bitcoin in 30days and 20% of their Provided Help in Local Currency. Participants who Upload their Picture will Earn their Complete 50% in Bitcoin in 30days and 30% for Local Currency in 30days. Participants who upload fake pictures will be Blocked when their ID is requested and compared with the uploaded picture. However, you cannot Get Help if you have not Provided Help. Request for providing help comes to you in your Dashboard. If you do not do it within 48 hours, you will be removed from the system. (For all eternity) In cases of any matter regarding the topic our Support Team are ready to help and answer all your questions.\r\n\r\n<h3>REGISTRATION BONUS</h3>\r\nWhen registering in the system, you get from $20 to $100 as a One Time Registration Bonus in Bitcoin and 4,000 to 20,000 as One Time Registration Bonus in Your Local Currency. Bonuses are given only once, not every time. Only those whoâ€™s first Pledge to Provide Help falls within the bonus range. (Otherwise the System will be pulled apart for bonuses). Registration Bonus Ranges for Bitcoin are as Follows;\r\n\r\n(1.) $20 Registration Bonus â€” if you have contributed from $50 to $499.\r\n(2.) $50 Registration Bonus â€” if you have contributed from $500 to $2''999.\r\n(3.) $100 Registration Bonus â€” if you have contributed from $3''000 and above.\r\n\r\nRegistration Bonus Ranges for Local Currencies are as Follows;\r\n\r\n(1.) 4,000 Registration Bonus â€” if you have contributed from 15,000 to 149,000.\r\n(2.) 10,000 Registration Bonus â€” if you have contributed from 150,000 to 649,000.\r\n(3.) 20,000 Registration Bonus â€” if you have contributed from 650,000 and above of your Local Currency.\r\n\r\nEach participant is allowed to have ONLY one account.\r\n\r\n<h3>Credibility Score Index</h3>\r\nCredibility Score index is a custom rating logic implemented into the system to ensure fair-play and honesty. Each new account is started with 100% rating, however, penalties are enforced when guidelines are broken, which lead to the deduction of score point. This is to ensure all members act and respond according to the guidelines of the community.\r\n\r\nKey Guidelines / Penalties include the following\r\nIf you are paired and you contact the money-receiving member, asking for a 24hr time extension to make payment, once he/she approves in his account, you will lose 25% of your credibility score.\r\nIf you offer to provide help and you cancel the offer (before you are paired), 5% score point will be deducted from your Credibility score card.\r\nIf discovered that your profile contains wrong profile picture/ information, 50% will be deducted.\r\nIf you fail to provide help to a paired member and expiry date elapses, your Credibility Score will be wiped out to Zero (0), which leads to automatic profile suspension.\r\nAccount/Profile suspension means you will no longer be able to place or receive help from this platform, for resolution, please send an email to support@YoursiteName .com .\r\n\r\nFinally, please note that Credibility Score value is a key input for the â€œautomatic pairing and assigning control systemâ€; this system is solely responding for matching requests to offers. Members will higher Credibility Score will have higher priority for paring\r\n\r\n<h3>REFERRAL BONUS</h3>\r\nYou get 10% from all deposits of the participant you invited. Inviting new members into the Community is your additional contribution to its development. But nobody force the members of the Community to invite new participants. But at the same time, understanding that the YoursiteName platform canâ€™t exist without development and participantsâ€™ encouragement in the form of referral bonuses motivate many people to take an active position.\r\n\r\n<h3>GUIDERS/SPONSORS</h3>\r\nThere are NO Guiders/Sponsors in this Platform. This platform has been DESIGNED TO LAST FOREVER and therefore does not support the excess payout privileges of Guiders which drains the system. The System ONLY Supports/Allows REPRESENTATIVES who are given extra 5%-10% of their Provide Help Offer in Addition to their 30% if they are participating in Local Currency or 50% if in Bitcoin in 30days depending on how hardworking they are.\r\n\r\n<h3>RECOMMITMENT</h3>\r\nYoursiteName is here to Change Lives, Change Nations and Change the World. It is designed to last forever; we understand that everyone will love to earn money every 30days. We will like to inform you that this Community will last forever if only everyone makes a New Pledge to Provide Another Help after they have Received Help knowing that the new Help you pledged to Provide will bring in another 30% in Local currency or 50% in Bitcoin in 30days. It will keep the community running forever. YoursiteName also implemented some featured to keep this in check.\r\n\r\na.) After receiving Help, a Participant is given a maximum of 3days to make another Pledge to Provide Help. If no new pledge is made after 3 days from receiving help, the system will commence debiting of your Credibility score. Please note that accounts with credibility score of zero(0) will be automatically blocked. All we need in this community are people who are active and have the Mind Set to Give and in turn Receive. We need people that will make this community last forever.\r\n\r\nb.) When a Participant makes a Pledge to Provide Help of $100 for Example; his/her next Pledge to Provide Help will not go below the Previous Help provided. It can only be the same or higher. This will keep the Community Growing instead of being setback by people who will give help of $1,000 for example and after Getting Help of $1,500 they decide to Provide Help of $10 in their next Pledge. Such doesnâ€™t work here on YoursiteName \r\n\r\nc.) Feel the joy of giving when you see the face of who you are Providing Help to. This will help Participants make friends all over the World. A Real Social Financial Platform with Sincere people Changing Lives, Changing Nations and Changing the World.\r\n', '../img/599d993cdbc24db1578653ebf49427fd.png', '<h3>VERY IMPORTANT!</h3>\r\nThere is NO Central Account where all the System money flows to (and where it can be easily stolen from). All the money is only on the banking accounts of the participants themselves, on a lot of thousand and million private accounts of participants.\r\n\r\nParticipants transfer to each other directly, without intermediaries, we only regulates the process - nothing more.\r\n', '<h3>About Us</h3>\r\nGET HELP WORLDWIDE (GHW) is not a bank, we do not collect your money, we are not an Online business, HYIP, investment, Loan or MLM program. GHW is a community where people help each other. We provide you a technical basic program/avenue, which helps millions of participants worldwide to find those who NEED help, and those who are ready to PROVIDE help for FREE.\r\n\r\nAll funds transferred to other participants are help given by your own good will to another one, absolutely gratis. If you are completely confident and certain in your actions and make your mind to participate, we kindly ask you to study carefully all warnings and instructions first. In cases of any matter regarding the topic our online consultants are ready to help and answer all your questions\r\n\r\n<h3>Non-profit oriented goals</h3>\r\nTGHW is a non-profit organization managed by a dedicated team of volunteers who are also Participants in the Platform. We are a non-denominational group, supported by the team members, who are committed to provide financial and social opportunities and to promote well being to everyone in world.\r\n', '<h3>A Community of helpers</h3>\r\nGHW is a community of people providing each other financial help on the principle of gratuitousness, reciprocity and benevolence. In GHW you donâ€™t have to make contracts or pledge your property, there are no lenders and no debtors. Everything is very simple: one participant asks for help â€“ another one helps. The only thing that GHW demands from its participants is to be honest and kind to each other. You ask for financial help when you need it, you give financial help when you are able to do it.\r\n\r\n<h3>Fund Dissemination</h3>\r\nGHW is committed to serve humanity by integrating resources for people in need. Recognize the innate worth of all people and the value of diversity, Work to ensure equal opportunity to everyone, irrespective of race, gender, color, class, ethnicity, disability and location. You can help one and will get help from others which will return you a 30% in Local Currency or 50% in Bitcoin in 30 days maximum. If you are willing to do so join us now\r\n', '2017-04-08 15:45:02');

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `donor_id` int(11) NOT NULL,
  `login_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `yield_amt` double NOT NULL,
  `request_amt` double NOT NULL,
  `received_amt` double NOT NULL,
  `bonus` double NOT NULL,
  `referral_bonus` double NOT NULL,
  `payment_method` varchar(100) NOT NULL,
  `donor_status` varchar(100) NOT NULL,
  `payment_status` varchar(100) NOT NULL,
  `matured_date` varchar(100) NOT NULL,
  `date_paid` datetime NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`donor_id`, `login_id`, `amount`, `yield_amt`, `request_amt`, `received_amt`, `bonus`, `referral_bonus`, `payment_method`, `donor_status`, `payment_status`, `matured_date`, `date_paid`, `date_added`) VALUES
(137, 9, 200000, 0, 200000, 0, 0, 0, 'Bank', 'Canceled', 'Paid', '2017-03-26 23:23:39', '2017-04-25 23:23:39', '2017-04-25 23:23:39'),
(138, 3, 100000, 0, 100000, 0, 0, 0, 'Bank', 'Matched', 'Paid', '2017-03-26 23:30:37', '2017-04-25 23:30:37', '2017-04-25 23:30:37');

-- --------------------------------------------------------

--
-- Table structure for table `make_payment`
--

CREATE TABLE `make_payment` (
  `id` int(11) NOT NULL,
  `login_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `payee_name` varchar(100) NOT NULL,
  `beneficiary_name` varchar(100) NOT NULL,
  `receiving_donor` int(11) NOT NULL,
  `sending_donor` int(11) NOT NULL,
  `match_date` datetime NOT NULL,
  `penalty_date` datetime NOT NULL,
  `amount` float NOT NULL,
  `recipient` varchar(100) NOT NULL,
  `payment_info` varchar(255) NOT NULL,
  `status` varchar(100) NOT NULL,
  `payment_proof` varchar(100) NOT NULL,
  `payment_date` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `match_donations`
--

CREATE TABLE `match_donations` (
  `match_id` int(11) NOT NULL,
  `match_to_donor_id` int(11) NOT NULL,
  `donating_id` int(11) NOT NULL,
  `payer_id` int(11) NOT NULL,
  `payee_id` int(11) NOT NULL,
  `m_amount` double NOT NULL,
  `paymt_method` varchar(100) NOT NULL,
  `match_status` varchar(100) NOT NULL,
  `date_matched` datetime NOT NULL,
  `period_timer` varchar(100) NOT NULL,
  `t_extension` varchar(100) NOT NULL,
  `date_paid` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `match_donations`
--

INSERT INTO `match_donations` (`match_id`, `match_to_donor_id`, `donating_id`, `payer_id`, `payee_id`, `m_amount`, `paymt_method`, `match_status`, `date_matched`, `period_timer`, `t_extension`, `date_paid`) VALUES
(127, 129, 129, 0, 0, 0, 'Bank', 'Paid', '2017-04-16 19:38:07', '', '', '2017-04-16 19:38:07'),
(131, 129, 125, 1, 2, 7000, 'Bank', 'Paid', '2017-04-16 19:40:58', '2017-04-18 19:40:58', '', '2017-04-16 19:45:03'),
(132, 129, 127, 9, 2, 30000, 'Bank', 'Paid', '2017-04-16 19:41:01', '2017-04-18 19:41:01', '', '2017-04-16 19:42:23'),
(133, 130, 130, 0, 0, 0, 'BitCoin', 'Paid', '2017-04-16 19:46:48', '', '', '2017-04-16 19:46:48'),
(134, 131, 131, 0, 0, 0, 'BitCoin', 'Paid', '2017-04-16 19:47:30', '', '', '2017-04-16 19:47:30'),
(135, 131, 128, 9, 1, 1000, 'BitCoin', 'Paid', '2017-04-16 19:48:26', '2017-04-18 19:48:26', '', '2017-04-16 19:49:43'),
(140, 136, 136, 0, 0, 0, 'Bank', 'Paid', '2017-04-25 23:22:05', '', '', '2017-04-25 23:22:05'),
(141, 137, 137, 0, 0, 0, 'Bank', 'Paid', '2017-04-25 23:23:39', '', '', '2017-04-25 23:23:39'),
(142, 138, 138, 0, 0, 0, 'Bank', 'Paid', '2017-04-25 23:30:37', '', '', '2017-04-25 23:30:37');

-- --------------------------------------------------------

--
-- Table structure for table `messaging`
--

CREATE TABLE `messaging` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `sender_name` varchar(100) NOT NULL,
  `reciever_id` int(11) NOT NULL,
  `receiver_name` varchar(100) NOT NULL,
  `subject` varchar(225) NOT NULL,
  `message` longtext NOT NULL,
  `file` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `ip` int(11) NOT NULL,
  `date_sent` datetime NOT NULL,
  `date_read` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `messaging`
--

INSERT INTO `messaging` (`id`, `sender_id`, `sender_name`, `reciever_id`, `receiver_name`, `subject`, `message`, `file`, `status`, `ip`, `date_sent`, `date_read`) VALUES
(1, 1, 'TestName', 1, 'Admin', 'Just checking', 'rererefr', '', 'Read', 0, '2017-04-05 00:00:00', ''),
(2, 1, 'TestName', 1, 'Admin', 'Just checking 3', 'rererefr', '', 'Read', 0, '2017-04-05 00:00:00', ''),
(8, 0, 'admin', 1, '', '', 'ereerer', '', 'Read', 0, '2017-04-05 18:01:31', ''),
(9, 0, 'admin', 1, '', '', 'fdfddsf', '', 'Read', 0, '2017-04-05 18:01:46', ''),
(10, 0, 'admin', 1, '', '', 'bgbdggfb', '', 'Read', 0, '2017-04-06 13:47:17', ''),
(11, 0, 'admin', 1, '', '', 'htrtttr', '', 'Read', 0, '2017-04-06 13:48:23', ''),
(12, 0, 'admin', 1, '', '', 'sssssssresb  rbgfdsr r rff fdf  g r rrssr', '', 'Read', 0, '2017-04-06 14:11:47', ''),
(13, 0, 'admin', 1, '', '', 'reerre', '', 'Read', 0, '2017-04-06 14:14:42', ''),
(14, 0, 'admin', 1, '', '', 'ssdsd', '', 'Read', 0, '2017-04-06 14:15:29', ''),
(15, 0, 'admin', 1, '', '', 'ree', '', 'Read', 0, '2017-04-06 14:17:04', ''),
(16, 0, 'admin', 1, '', '', 'fdffds', '', 'Read', 0, '2017-04-06 14:18:27', ''),
(17, 0, 'admin', 1, '', '', 'fdffds', '../img/c701eb25829d257e6a9bdc7fdef78468.png', 'Read', 0, '2017-04-06 14:18:45', ''),
(18, 0, 'admin', 1, '', '', 'gffghfhhj', '../img/4876814c5b4e9aef7e0836c8ba7326dc.jpg', 'Read', 0, '2017-04-06 14:20:18', ''),
(19, 0, 'admin', 1, '', '', 'Goood worrkkk', '../img/a8a68bcd9d8370362a6a7d78675e2e69.jpg', 'Read', 0, '2017-04-06 14:31:12', ''),
(20, 0, 'admin', 1, '', '', 'user end', '../img/d42b31b6970265f6f1941e818520077e.png', 'Read', 0, '2017-04-06 14:49:01', ''),
(21, 0, 'oziconnect', 1, '', '', 'User Test 2', '', 'Read', 0, '2017-04-06 14:51:40', ''),
(22, 0, 'oziconnect', 1, '', '', 'user test 3 ', '../img/feb62d0eb22d9a57e5ed71aaf62f48ab.jpg', 'Read', 0, '2017-04-06 14:51:57', ''),
(23, 1, 'Harrison Ozi', 0, '', 'Bandwith Issues', 'ddfdgdfgddgdfgfddgf', '', 'Read', 0, '2017-04-07 19:29:13', ''),
(24, 0, 'oziconnect', 1, '', '', 'rrerrerggrere', '', 'Read', 0, '2017-04-07 19:35:47', ''),
(25, 1, 'oziconnect', 0, '', 'A review of your Project', 'Contact Support\r\n\r\nFor Fast resolution to your issues / questions, please use the form below to contact us;', '../img/e243f30284c139a79c7ae6ac07de9dcf.txt', 'Read', 0, '2017-04-07 19:37:02', '');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `admin` varchar(100) NOT NULL,
  `title` varchar(225) NOT NULL,
  `note` text NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `admin`, `title`, `note`, `date_added`) VALUES
(1, 'admin', 'just testing', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '2017-02-08 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `payment_proof`
--

CREATE TABLE `payment_proof` (
  `id` int(11) NOT NULL,
  `match_id` int(11) NOT NULL,
  `payer_id` int(11) NOT NULL,
  `payee_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `payment_info` varchar(225) NOT NULL,
  `proof` varchar(100) NOT NULL,
  `pay_method` varchar(100) NOT NULL,
  `pay_status` varchar(100) NOT NULL,
  `date_added` datetime NOT NULL,
  `confirmed_date` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment_proof`
--

INSERT INTO `payment_proof` (`id`, `match_id`, `payer_id`, `payee_id`, `amount`, `payment_info`, `proof`, `pay_method`, `pay_status`, `date_added`, `confirmed_date`) VALUES
(57, 57, 1, 2, 100, '<b>Phone: </b>,<br><b>Bitcoin: </b>', '../user/img/payment-proof/4f86c08b7a84f2305358f700f2610e65.png', 'BitCoin', 'Paid', '2017-03-18 10:45:00', '2017-03-18 10:50:54'),
(58, 59, 3, 1, 5000, '<b>Phone: </b>08037372777,<br><b>Acct Name: </b>Harrison Oziegbe,<br> <b>Acct #</b>: 0987643200, <br> <b>Bank: </b>First Bank Plc', '../user/img/payment-proof/9a3796ee8eb3d3e3c3fa76919a2e4c56.png', 'Bank', 'Paid', '2017-03-18 10:52:52', '2017-03-18 11:23:19'),
(59, 61, 3, 1, 5000, '<b>Phone: </b>08037372777,<br><b>Acct Name: </b>Harrison Oziegbe,<br> <b>Acct #</b>: 0987643200, <br> <b>Bank: </b>First Bank Plc', '../user/img/payment-proof/03c45e6f94bfc1934bd3f9987e6480b0.png', 'Bank', 'Paid', '2017-03-19 00:17:33', '2017-03-19 00:17:59'),
(60, 62, 2, 3, 6751, '<b>Phone: </b>2348037372777,<br><b>Acct Name: </b>Harrison Oziegbe,<br> <b>Acct #</b>: 0909090988, <br> <b>Bank: </b>First Bank Plc', '../user/img/payment-proof/a622bcc99fe5851e4418150a1e3fa099.jpg', 'Bank', 'Paid', '2017-03-19 17:28:36', '2017-03-19 17:35:07'),
(61, 63, 1, 3, 6751, '<b>Phone: </b>2348037372777,<br><b>Acct Name: </b>Harrison Oziegbe,<br> <b>Acct #</b>: 0909090988, <br> <b>Bank: </b>First Bank Plc', '../user/img/payment-proof/31a5177a99ba244d14a482f677ecd2e3.png', 'Bank', 'Paid', '2017-03-19 20:18:39', '2017-03-19 20:21:12'),
(63, 66, 2, 1, 10000, '<b>Phone: </b>08037372777,<br><b>Acct Name: </b>Harrison Oziegbe,<br> <b>Acct #</b>: 0987643200, <br> <b>Bank: </b>First Bank Plc', '../user/img/payment-proof/31471b8c02f572e68d75ec515e940ad7.png', 'Bank', 'Paid', '2017-04-10 19:58:38', '2017-04-10 21:02:22'),
(65, 70, 1, 14, 400, '<b>Phone: </b>234455454454554,<br><b>Bitcoin: </b>bitcoin address', '../user/img/payment-proof/58c6ad372766167056be9b87281e669b.jpg', 'BitCoin', 'Paid', '2017-04-11 13:46:42', '2017-04-11 13:59:02'),
(66, 71, 3, 1, 300, '<b>Phone: </b>08037372777,<br><b>Bitcoin: </b>myjustgateerererreer', '../user/img/payment-proof/b0011b1253f4d43279e032f2cba9ac2a.jpg', 'BitCoin', 'Paid', '2017-04-11 14:01:49', '2017-04-11 14:08:04'),
(67, 72, 1, 3, 200, '<b>Phone: </b>2348037372777,<br><b>Bitcoin: </b>dhzdhtrfjtyku', '../user/img/payment-proof/a3cb3f46fc62b68f0f19789c5472560a.png', 'BitCoin', 'Paid', '2017-04-11 14:08:41', '2017-04-11 14:09:51'),
(68, 74, 14, 2, 50000, '<b>Phone: </b>0987675456543,<br><b>Acct Name: </b>Harrison Oziegbe,<br> <b>Acct #</b>: 0987654321, <br> <b>Bank: </b>First Bank', '../user/img/payment-proof/c45fb1ea6c55bbf2d2bbba9bcac5f131.jpg', 'Bank', 'Paid', '2017-04-14 06:57:28', '2017-04-14 07:03:19'),
(69, 76, 14, 3, 50000, '<b>Phone: </b>2348037372777,<br><b>Acct Name: </b>Harrison Oziegbe,<br> <b>Acct #</b>: 0909090988, <br> <b>Bank: </b>First Bank Plc', '../user/img/payment-proof/90275114e0b625281a0d0a363c4e31b6.png', 'Bank', 'Paid', '2017-04-14 06:59:20', '2017-04-14 07:03:08'),
(70, 78, 1, 3, 1000, '<b>Phone: </b>2348037372777,<br><b>Bitcoin: </b>dhzdhtrfjtyku', '../user/img/payment-proof/cf3465fcde9806e4d555499b120c9a24.png', 'BitCoin', 'Awaiting Confirmation', '2017-04-14 07:03:51', ''),
(71, 82, 14, 9, 1000, '<b>Phone: </b>2347053221116,<br><b>Bitcoin: </b>bitcoin address', '', 'BitCoin', 'Pending', '2017-04-14 07:21:17', ''),
(72, 85, 14, 3, 1000, '<b>Phone: </b>2348037372777,<br><b>Bitcoin: </b>dhzdhtrfjtyku', '../user/img/payment-proof/68abf80cdc91c0f33e865270fc409202.png', 'BitCoin', 'Paid', '2017-04-14 07:35:49', '2017-04-14 07:38:11'),
(73, 86, 14, 3, 10000, '<b>Phone: </b>2348037372777,<br><b>Acct Name: </b>Harrison Oziegbe,<br> <b>Acct #</b>: 0909090988, <br> <b>Bank: </b>First Bank Plc', '../user/img/payment-proof/0dc0bd870198316c8cd023ae346d492b.jpg', 'Bank', 'Paid', '2017-04-14 07:36:27', '2017-04-14 07:38:07'),
(74, 88, 3, 2, 20000, '<b>Phone: </b>0987675456543,<br><b>Acct Name: </b>Harrison Oziegbe,<br> <b>Acct #</b>: 0987654321, <br> <b>Bank: </b>First Bank', '', 'Bank', 'Pending', '2017-04-14 22:55:20', ''),
(75, 89, 14, 2, 10000, '<b>Phone: </b>0987675456543,<br><b>Acct Name: </b>Harrison Oziegbe,<br> <b>Acct #</b>: 0987654321, <br> <b>Bank: </b>First Bank', '../user/img/payment-proof/5f5382e8125561cbbd65600a5ad9e8ab.png', 'Bank', 'Paid', '2017-04-14 23:00:06', '2017-04-14 23:00:33'),
(76, 90, 3, 2, 20000, '<b>Phone: </b>0987675456543,<br><b>Acct Name: </b>Harrison Oziegbe,<br> <b>Acct #</b>: 0987654321, <br> <b>Bank: </b>First Bank', '../user/img/payment-proof/ecd55606af2e9cf9d5552127fd3aff9e.jpg', 'Bank', 'Paid', '2017-04-14 23:01:41', '2017-04-14 23:02:04'),
(77, 92, 14, 3, 10000, '<b>Phone: </b>2348037372777,<br><b>Acct Name: </b>Harrison Oziegbe,<br> <b>Acct #</b>: 0909090988, <br> <b>Bank: </b>First Bank Plc', '', 'Bank', 'Pending', '2017-04-14 23:21:16', ''),
(78, 93, 14, 3, 10000, '<b>Phone: </b>2348037372777,<br><b>Acct Name: </b>Harrison Oziegbe,<br> <b>Acct #</b>: 0909090988, <br> <b>Bank: </b>First Bank Plc', '', 'Bank', 'Pending', '2017-04-14 23:25:43', ''),
(79, 94, 14, 3, 10000, '<b>Phone: </b>2348037372777,<br><b>Acct Name: </b>Harrison Oziegbe,<br> <b>Acct #</b>: 0909090988, <br> <b>Bank: </b>First Bank Plc', '', 'Bank', 'Pending', '2017-04-14 23:33:58', ''),
(80, 97, 14, 9, 1000, '<b>Phone: </b>2347053221116,<br><b>Bitcoin: </b>bitcoin address', '', 'BitCoin', 'Pending', '2017-04-15 00:20:54', ''),
(81, 98, 1, 9, 500, '<b>Phone: </b>2347053221116,<br><b>Bitcoin: </b>bitcoin address', '', 'BitCoin', 'Pending', '2017-04-15 00:21:08', ''),
(82, 99, 11, 9, 100, '<b>Phone: </b>2347053221116,<br><b>Bitcoin: </b>bitcoin address', '', 'BitCoin', 'Pending', '2017-04-15 00:21:10', ''),
(83, 100, 14, 9, 1000, '<b>Phone: </b>2347053221116,<br><b>Bitcoin: </b>bitcoin address', '', 'BitCoin', 'Pending', '2017-04-15 00:34:14', ''),
(84, 101, 14, 9, 1000, '<b>Phone: </b>2347053221116,<br><b>Bitcoin: </b>bitcoin address', '', 'BitCoin', 'Pending', '2017-04-15 00:36:58', ''),
(85, 102, 1, 9, 500, '<b>Phone: </b>2347053221116,<br><b>Bitcoin: </b>bitcoin address', '', 'BitCoin', 'Pending', '2017-04-15 00:37:00', ''),
(86, 103, 11, 9, 100, '<b>Phone: </b>2347053221116,<br><b>Bitcoin: </b>bitcoin address', '', 'BitCoin', 'Pending', '2017-04-15 00:37:03', ''),
(87, 104, 3, 9, 5000, '<b>Phone: </b>2347053221116,<br><b>Acct Name: </b>dgfgf,<br> <b>Acct #</b>: 09875432, <br> <b>Bank: </b>First Bank Plc', '', 'Bank', 'Pending', '2017-04-15 00:43:14', ''),
(88, 106, 3, 9, 2000, '<b>Phone: </b>2347053221116,<br><b>Acct Name: </b>dgfgf,<br> <b>Acct #</b>: 09875432, <br> <b>Bank: </b>First Bank Plc', '', 'Bank', 'Pending', '2017-04-15 00:49:43', ''),
(89, 107, 14, 9, 1000, '<b>Phone: </b>2347053221116,<br><b>Bitcoin: </b>bitcoin address', '', 'BitCoin', 'Pending', '2017-04-15 00:59:20', ''),
(90, 108, 1, 9, 500, '<b>Phone: </b>2347053221116,<br><b>Bitcoin: </b>bitcoin address', '', 'BitCoin', 'Pending', '2017-04-15 00:59:23', ''),
(91, 109, 11, 9, 100, '<b>Phone: </b>2347053221116,<br><b>Bitcoin: </b>bitcoin address', '', 'BitCoin', 'Pending', '2017-04-15 00:59:25', ''),
(92, 110, 3, 9, 2000, '<b>Phone: </b>2347053221116,<br><b>Bitcoin: </b>bitcoin address', '', 'BitCoin', 'Pending', '2017-04-15 01:02:41', ''),
(93, 111, 11, 9, 100, '<b>Phone: </b>2347053221116,<br><b>Bitcoin: </b>bitcoin address', '', 'BitCoin', 'Pending', '2017-04-15 01:05:05', ''),
(94, 112, 14, 9, 1000, '<b>Phone: </b>2347053221116,<br><b>Bitcoin: </b>bitcoin address', '', 'BitCoin', 'Pending', '2017-04-15 01:05:18', ''),
(95, 113, 1, 9, 500, '<b>Phone: </b>2347053221116,<br><b>Bitcoin: </b>bitcoin address', '', 'BitCoin', 'Pending', '2017-04-15 01:05:20', ''),
(96, 114, 3, 9, 2000, '<b>Phone: </b>2347053221116,<br><b>Bitcoin: </b>bitcoin address', '../user/img/payment-proof/6cacfdd956daa6d8ad4265207b81a4d0.png', 'BitCoin', 'Paid', '2017-04-15 01:17:03', '2017-04-15 01:34:01'),
(97, 116, 3, 1, 2000, '<b>Phone: </b>08037372777,<br><b>Acct Name: </b>Harrison Oziegbe,<br> <b>Acct #</b>: 0987643200, <br> <b>Bank: </b>First Bank Plc', '../user/img/payment-proof/cf7e8375f57121c19f9c34a109b5251b.png', 'Bank', 'Paid', '2017-04-15 01:43:45', '2017-04-15 01:46:16'),
(98, 117, 3, 1, 2000, '<b>Phone: </b>08037372777,<br><b>Acct Name: </b>Harrison Oziegbe,<br> <b>Acct #</b>: 0987643200, <br> <b>Bank: </b>First Bank Plc', '../user/img/payment-proof/b1e4fe6e8a4f98d1d5f436fbb450643a.png', 'Bank', 'Paid', '2017-04-15 10:25:03', '2017-04-15 10:29:39'),
(99, 121, 3, 1, 2000, '<b>Phone: </b>08037372777,<br><b>Acct Name: </b>Harrison Oziegbe,<br> <b>Acct #</b>: 0987643200, <br> <b>Bank: </b>First Bank Plc', '../user/img/payment-proof/dc4f2a3257f51f64c2155e420586e8f6.jpg', 'Bank', 'Paid', '2017-04-15 10:49:06', '2017-04-15 10:51:10'),
(100, 123, 3, 9, 5000, '<b>Phone: </b>2347053221116,<br><b>Acct Name: </b>dgfgf,<br> <b>Acct #</b>: 09875432, <br> <b>Bank: </b>First Bank Plc', '', 'Bank', 'Pending', '2017-04-16 19:32:06', ''),
(101, 124, 1, 9, 7000, '<b>Phone: </b>2347053221116,<br><b>Acct Name: </b>dgfgf,<br> <b>Acct #</b>: 09875432, <br> <b>Bank: </b>First Bank Plc', '', 'Bank', 'Pending', '2017-04-16 19:32:08', ''),
(102, 125, 3, 9, 5000, '<b>Phone: </b>2347053221116,<br><b>Acct Name: </b>dgfgf,<br> <b>Acct #</b>: 09875432, <br> <b>Bank: </b>First Bank Plc', '', 'Bank', 'Pending', '2017-04-16 19:32:52', ''),
(103, 126, 1, 9, 7000, '<b>Phone: </b>2347053221116,<br><b>Acct Name: </b>dgfgf,<br> <b>Acct #</b>: 09875432, <br> <b>Bank: </b>First Bank Plc', '', 'Bank', 'Pending', '2017-04-16 19:33:00', ''),
(104, 128, 3, 2, 5000, '<b>Phone: </b>0987675456543,<br><b>Acct Name: </b>Harrison Oziegbe,<br> <b>Acct #</b>: 0987654321, <br> <b>Bank: </b>First Bank', '', 'Bank', 'Pending', '2017-04-16 19:39:44', ''),
(105, 129, 1, 2, 7000, '<b>Phone: </b>0987675456543,<br><b>Acct Name: </b>Harrison Oziegbe,<br> <b>Acct #</b>: 0987654321, <br> <b>Bank: </b>First Bank', '', 'Bank', 'Pending', '2017-04-16 19:39:46', ''),
(106, 130, 3, 2, 5000, '<b>Phone: </b>0987675456543,<br><b>Acct Name: </b>Harrison Oziegbe,<br> <b>Acct #</b>: 0987654321, <br> <b>Bank: </b>First Bank', '../user/img/payment-proof/201f98862edeab77dcdbd7ca55660dee.png', 'Bank', 'Paid', '2017-04-16 19:40:56', '2017-04-16 19:43:53'),
(107, 131, 1, 2, 7000, '<b>Phone: </b>0987675456543,<br><b>Acct Name: </b>Harrison Oziegbe,<br> <b>Acct #</b>: 0987654321, <br> <b>Bank: </b>First Bank', '../user/img/payment-proof/f7b9e1dcd4f1bfd66c75b0fc127f5cea.png', 'Bank', 'Paid', '2017-04-16 19:40:58', '2017-04-16 19:45:03'),
(108, 132, 9, 2, 30000, '<b>Phone: </b>0987675456543,<br><b>Acct Name: </b>Harrison Oziegbe,<br> <b>Acct #</b>: 0987654321, <br> <b>Bank: </b>First Bank', '../user/img/payment-proof/9b097b3b4c6a7d1fdc8b5ea577ce261b.jpg', 'Bank', 'Paid', '2017-04-16 19:41:01', '2017-04-16 19:42:23'),
(109, 135, 9, 1, 1000, '<b>Phone: </b>08037372777,<br><b>Bitcoin: </b>myjustgateerererreer', '../user/img/payment-proof/ff98e337eb578204b476eaeb60393f96.jpg', 'BitCoin', 'Paid', '2017-04-16 19:48:26', '2017-04-16 19:49:43'),
(110, 136, 3, 2, 5000, '<b>Phone: </b>0987675456543,<br><b>Acct Name: </b>Harrison Oziegbe,<br> <b>Acct #</b>: 0987654321, <br> <b>Bank: </b>First Bank', '', 'Bank', 'Pending', '2017-04-17 16:59:30', ''),
(111, 137, 3, 2, 50000, '<b>Phone: </b>0987675456543,<br><b>Acct Name: </b>Harrison Oziegbe,<br> <b>Acct #</b>: 0987654321, <br> <b>Bank: </b>First Bank', '', 'Bank', 'Pending', '2017-04-17 17:57:48', ''),
(112, 143, 2, 3, 100000, '<b>Phone: </b>,<br><b>Acct Name: </b>,<br> <b>Acct #</b>: , <br> <b>Bank: </b>', '', 'Bank', 'Pending', '2017-05-02 23:33:50', '');

-- --------------------------------------------------------

--
-- Table structure for table `percentaging`
--

CREATE TABLE `percentaging` (
  `id` int(11) NOT NULL,
  `btc_donation` double NOT NULL,
  `local_donation` double NOT NULL,
  `bonus_btc_o` double NOT NULL,
  `bonus_btc_t` double NOT NULL,
  `bonus_btc_th` double NOT NULL,
  `bonus_local_o` double NOT NULL,
  `bonus_local_t` double NOT NULL,
  `bonus_local_th` double NOT NULL,
  `referral_bonus` double NOT NULL,
  `ref_times` double NOT NULL,
  `last_updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `percentaging`
--

INSERT INTO `percentaging` (`id`, `btc_donation`, `local_donation`, `bonus_btc_o`, `bonus_btc_t`, `bonus_btc_th`, `bonus_local_o`, `bonus_local_t`, `bonus_local_th`, `referral_bonus`, `ref_times`, `last_updated`) VALUES
(1, 60, 40, 10, 50, 100, 4000, 10000, 20000, 10, 1, '2017-03-30 23:53:19');

-- --------------------------------------------------------

--
-- Table structure for table `receive_payment`
--

CREATE TABLE `receive_payment` (
  `id` int(11) NOT NULL,
  `login_id` int(11) NOT NULL,
  `payee_id` int(11) NOT NULL,
  `payee_name` varchar(100) NOT NULL,
  `beneficiary_name` varchar(100) NOT NULL,
  `receiving_donor` int(11) NOT NULL,
  `sending_donor` int(11) NOT NULL,
  `match_date` datetime NOT NULL,
  `penalty_date` datetime NOT NULL,
  `amount` float NOT NULL,
  `recipient` varchar(100) NOT NULL,
  `payment_info` varchar(255) NOT NULL,
  `status` varchar(100) NOT NULL,
  `payment_proof` varchar(100) NOT NULL,
  `payment_date` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
  `id` int(11) NOT NULL,
  `login_id` int(11) NOT NULL,
  `member` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `reg_date` datetime NOT NULL,
  `help_offered` varchar(255) NOT NULL,
  `score` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `referrals`
--

INSERT INTO `referrals` (`id`, `login_id`, `member`, `country`, `phone`, `email`, `status`, `reg_date`, `help_offered`, `score`) VALUES
(1, 1, 'John Doe', 'Nigeria', '0808373737', 'info@yahoo.com', '', '2017-01-27 00:00:00', '3000', '80%'),
(2, 1, 'Matt Gary', 'Nigeria', '08037372777', 'info@creativeweb.com.ng', '', '2017-01-27 00:00:00', '600', '90%');

-- --------------------------------------------------------

--
-- Table structure for table `referral_bonus`
--

CREATE TABLE `referral_bonus` (
  `id` int(11) NOT NULL,
  `login_id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `member` varchar(100) NOT NULL,
  `referral_username` varchar(100) NOT NULL,
  `donation_amt` float NOT NULL,
  `bonus` float NOT NULL,
  `payment_method` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `date_used` varchar(100) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `referral_bonus`
--

INSERT INTO `referral_bonus` (`id`, `login_id`, `donor_id`, `member`, `referral_username`, `donation_amt`, `bonus`, `payment_method`, `status`, `date_used`, `date_added`) VALUES
(16, 3, 57, 'ozi', 'myName', 5000, 500, 'Bank', 'Used', '2017-03-19 17:28:17', '2017-03-18 10:40:49'),
(17, 1, 58, 'oziconnect', 'myName', 100, 10, 'BitCoin', 'Used', '2017-03-19 17:28:17', '2017-03-18 10:41:31'),
(18, 1, 63, 'oziconnect', 'myName', 6751, 675.1, 'Bank', 'Used', '2017-03-21 00:23:23', '2017-03-18 11:32:03'),
(21, 13, 69, 'harrisonworld', 'ozi', 6750, 675, 'Bank', 'Pending', '', '2017-03-21 01:06:02'),
(22, 1, 93, 'oziconnect', 'myName', 1000, 100, 'BitCoin', 'Used', '2017-04-28 12:09:56', '2017-04-14 07:03:51'),
(23, 3, 102, 'ozi', 'myName', 20000, 2000, 'Bank', 'Used', '2017-04-28 12:09:56', '2017-04-14 21:45:44'),
(24, 1, 107, 'oziconnect', 'myName', 500, 50, 'BitCoin', 'Used', '2017-04-28 12:09:56', '2017-04-14 23:55:24'),
(25, 11, 108, 'asapp24', 'ozitech', 100, 10, 'BitCoin', 'Pending', '', '2017-04-14 23:57:11'),
(26, 3, 112, 'ozi', 'myName', 5000, 500, 'Bank', 'Used', '2017-04-28 12:09:56', '2017-04-15 00:43:13'),
(27, 3, 114, 'ozi', 'myName', 2000, 200, 'Bank', 'Used', '2017-04-28 12:09:56', '2017-04-15 00:49:43'),
(28, 3, 115, 'ozi', 'myName', 2000, 200, 'Bank', 'Used', '2017-04-28 12:09:56', '2017-04-15 00:53:56'),
(29, 3, 116, 'ozi', 'myName', 2000, 200, 'Bank', 'Used', '2017-04-28 12:09:56', '2017-04-15 00:57:17'),
(30, 3, 122, 'ozi', 'myName', 2000, 200, 'Bank', 'Used', '2017-04-28 12:09:56', '2017-04-15 10:47:21'),
(31, 3, 124, 'ozi', 'myName', 5000, 500, 'Bank', 'Used', '2017-04-28 12:09:56', '2017-04-16 19:29:43'),
(32, 1, 125, 'oziconnect', 'myName', 7000, 700, 'Bank', 'Used', '2017-04-28 12:09:56', '2017-04-16 19:30:12'),
(33, 9, 127, 'ozitech', 'ozi', 30000, 3000, 'Bank', 'Used', '2017-04-17 16:59:29', '2017-04-16 19:36:53'),
(34, 3, 134, 'ozi', 'myName', 55000, 5500, 'Bank', 'Pending', '', '2017-04-17 18:27:06'),
(35, 2, 139, 'myName', 'oziconnect', 20000, 2000, 'Bank', 'Pending', '', '2017-04-28 12:09:56');

-- --------------------------------------------------------

--
-- Table structure for table `request_history`
--

CREATE TABLE `request_history` (
  `id` int(11) NOT NULL,
  `login_id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `request_date` datetime NOT NULL,
  `total_amt` float NOT NULL,
  `amt_paid` float NOT NULL,
  `outstanding_amt` float NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `support`
--

CREATE TABLE `support` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `sender_name` varchar(100) NOT NULL,
  `sender_email` varchar(100) NOT NULL,
  `sender_phone` varchar(100) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(100) NOT NULL,
  `date_sent` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `support`
--

INSERT INTO `support` (`id`, `sender_id`, `sender_name`, `sender_email`, `sender_phone`, `subject`, `message`, `status`, `date_sent`) VALUES
(4, 2, 'Harrison Oziegbe', 'ozi_harrisonworld2008@yahoo.com', '2347053221116', 'Web Developemnt Proposal - Real Estate', 'Contact Support\r\n\r\nFor Fast resolution to your issues / questions, please use the form below to contact us;Contact Support\r\n\r\nFor Fast resolution to your issues / questions, please use the form below to contact us;', 'Read', '2017-02-08 16:11:18'),
(10, 2, 'admin', '', '', 'Administrative details', 'dfEASFEASf', 'Read', '2017-02-11 19:54:22'),
(11, 2, 'admin', '', '', 'Administrative details', 'ok', 'Read', '2017-02-11 19:54:46'),
(12, 2, 'admin', '', '', 'Administrative details', 'jmfhjdfdfdg', 'Unread', '2017-02-13 11:24:55');

-- --------------------------------------------------------

--
-- Table structure for table `testimonies`
--

CREATE TABLE `testimonies` (
  `id` int(11) NOT NULL,
  `login_id` int(11) NOT NULL,
  `pay_id` int(11) NOT NULL,
  `member` varchar(100) NOT NULL,
  `amount` float NOT NULL,
  `message` text NOT NULL,
  `status` varchar(100) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `testimonies`
--

INSERT INTO `testimonies` (`id`, `login_id`, `pay_id`, `member`, `amount`, `message`, `status`, `date_added`) VALUES
(1, 1, 59, 'oziconnect', 5000, 'dfsdgfsdvb   v r srf r  sdrgrds gfre rehyt grejytn yu6mium 7m yug yjt fsgsdf Yesss.', 'Approved', '2017-03-19 00:15:56'),
(2, 1, 61, 'oziconnect', 5000, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s', 'Approved', '2017-03-19 00:22:12'),
(3, 2, 57, 'myName', 100, 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don''t look even slightly believable', 'Approved', '2017-03-19 15:31:29'),
(4, 2, 131, 'myName', 7000, 'tettrtrtrtrrt Dashboard / Receive Help / Write Testimony', 'Pending', '2017-04-28 12:15:28'),
(5, 2, 132, 'myName', 30000, 'Dashboard / Receive Help / Write Testimony Dashboard / Receive Help / Write Testimony', 'Pending', '2017-04-28 12:57:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `login_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `credibility_score` double NOT NULL,
  `country` varchar(100) NOT NULL,
  `photo` varchar(100) NOT NULL,
  `referral` varchar(100) NOT NULL,
  `referral_bonus` double NOT NULL,
  `available_bonus` double NOT NULL,
  `available_dollar` double NOT NULL,
  `status` varchar(100) NOT NULL,
  `signup_ip` varchar(100) NOT NULL,
  `signup_date` datetime NOT NULL,
  `user_timer` datetime NOT NULL,
  `last_login_ip` varchar(100) NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`login_id`, `username`, `email`, `phone`, `password`, `full_name`, `credibility_score`, `country`, `photo`, `referral`, `referral_bonus`, `available_bonus`, `available_dollar`, `status`, `signup_ip`, `signup_date`, `user_timer`, `last_login_ip`, `last_login`, `last_updated`) VALUES
(1, 'oziconnect', 'zirimzi@yah777o.com', '08037372777', '$2y$10$ofurAP2Ts4a/eiOEfRA21OorQ4TBIwXeVzPB05WIGdUsPYvhmpzRe', 'Harrison Ozi', 0, 'Nigeria', '../user/img/f3c9f7c87e1a8e69746b89b145fad729.jpg', 'myName', 0, 0, 0, 'Active', '', '2017-01-26 09:35:19', '2017-05-02 23:36:12', '', '2017-05-02 22:36:12', '0000-00-00 00:00:00'),
(2, 'myName', 'ozi_harrisonworld2008@yahoo.com', '0987675456543', '$2y$10$OgBUDRcnra2p58ybPnpvD.JmxBeUDmTymQfbckXbMRr10FYYnh4.S', 'Harrison Oziegbe', 90, 'Nigeria', '../user/img/4ff7c90e254b05433305825a1eb9d5f9.jpg', 'oziconnect', 0, 4500, 0, 'Active', '', '2017-01-30 12:22:46', '2017-05-02 23:36:12', '', '2017-05-02 22:36:12', '0000-00-00 00:00:00'),
(10, 'oziconnect2', 'ozforld2008@yahoo.com', '2348053221116', '$2y$10$Y2Se5VmT5GtLbyPXubZtcOl6sLWrLQRJq03p4ST0Vo06EH19eeYtS', 'Harrison Oziegbe', 0, 'Nigeria', '', 'ozitech', 0, 0, 0, 'Active', '', '2017-03-08 17:32:20', '2017-05-02 23:36:13', '', '2017-05-02 22:36:13', '2017-03-08 17:32:20'),
(11, 'asapp24', 'ozi_herrld2008@yahoo.com', '2347443221116', '$2y$10$bFailU.z2HGCeWHsu3/b6OQM1xMsf9LlGzKPNYqNM1LCBqxN6XnsS', 'Harrison Oziegbe', 5, 'Aruba', '', 'ozitech', 0, 0, 0, 'Active', '', '2017-03-08 17:35:17', '2017-05-02 23:36:13', '', '2017-05-02 22:36:13', '2017-03-08 17:35:17'),
(12, 'efeawfw', 'ozihss@rocketmail.com', '234803737543', '$2y$10$8wLE0NX4dLO1eDZhhmPAvev7OkLNJpDJI9GXkNL3mgAQwWpIiXL5y', 'John', 20, 'Nigeria', '', 'oziconnect', 0, 0, 0, 'Active', '', '2017-03-10 19:45:59', '2017-05-02 23:36:13', '', '2017-05-02 22:36:13', '2017-03-10 19:45:59'),
(13, 'harrisonworld', 'world2008@yahoo.com', '234989844324', '$2y$10$FaCjQYIGpRJNlXTSN/CbNu.jpGWDaNDpomUJJZfPyfCzImoErbZIq', 'Harrison', 0, 'Nigeria', '', 'ozi', 0, 0, 0, 'Active', '', '2017-03-18 15:11:02', '2017-05-02 23:36:13', '', '2017-05-02 22:36:13', '2017-03-18 15:11:02'),
(14, 'myAdminTest', 'admin@yahoo.com', '234455454454554', '$2y$10$ofurAP2Ts4a/eiOEfRA21OorQ4TBIwXeVzPB05WIGdUsPYvhmpzRe', 'myTest Name', 145, 'Nigeria', '', '', 0, 0, 0, 'Active', '', '2017-04-11 13:34:18', '2017-05-02 23:36:13', '', '2017-05-02 22:36:13', '2017-04-11 13:34:18');

-- --------------------------------------------------------

--
-- Table structure for table `website_settings`
--

CREATE TABLE `website_settings` (
  `id` int(11) NOT NULL,
  `site_name` varchar(100) NOT NULL,
  `site_title` varchar(100) NOT NULL,
  `site_description` varchar(255) NOT NULL,
  `favicon_url` varchar(100) NOT NULL,
  `logo_url` varchar(100) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `website_settings`
--

INSERT INTO `website_settings` (`id`, `site_name`, `site_title`, `site_description`, `favicon_url`, `logo_url`, `last_updated`) VALUES
(1, 'Myname2', 'Welcome to Myname', 'myname is P2P donation platform', '../img/68993cda0e496d4b47aaef5e37e3ef65.png', '../img/2184fbd7e56120092ebd094a8b014b58.png', '2017-03-31 13:20:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_details`
--
ALTER TABLE `account_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `admin_logged_in_users`
--
ALTER TABLE `admin_logged_in_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_login_activity`
--
ALTER TABLE `admin_login_activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_notes`
--
ALTER TABLE `admin_notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_notice`
--
ALTER TABLE `admin_notice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contents`
--
ALTER TABLE `contents`
  ADD PRIMARY KEY (`c_id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`donor_id`);

--
-- Indexes for table `make_payment`
--
ALTER TABLE `make_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `match_donations`
--
ALTER TABLE `match_donations`
  ADD PRIMARY KEY (`match_id`);

--
-- Indexes for table `messaging`
--
ALTER TABLE `messaging`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_proof`
--
ALTER TABLE `payment_proof`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `match_id` (`match_id`);

--
-- Indexes for table `percentaging`
--
ALTER TABLE `percentaging`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referrals`
--
ALTER TABLE `referrals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referral_bonus`
--
ALTER TABLE `referral_bonus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `donor_id` (`donor_id`);

--
-- Indexes for table `request_history`
--
ALTER TABLE `request_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support`
--
ALTER TABLE `support`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonies`
--
ALTER TABLE `testimonies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`login_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `website_settings`
--
ALTER TABLE `website_settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_details`
--
ALTER TABLE `account_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `admin_logged_in_users`
--
ALTER TABLE `admin_logged_in_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT for table `admin_login_activity`
--
ALTER TABLE `admin_login_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT for table `admin_notes`
--
ALTER TABLE `admin_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `admin_notice`
--
ALTER TABLE `admin_notice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `contents`
--
ALTER TABLE `contents`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `donor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;
--
-- AUTO_INCREMENT for table `make_payment`
--
ALTER TABLE `make_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `match_donations`
--
ALTER TABLE `match_donations`
  MODIFY `match_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;
--
-- AUTO_INCREMENT for table `messaging`
--
ALTER TABLE `messaging`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `payment_proof`
--
ALTER TABLE `payment_proof`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;
--
-- AUTO_INCREMENT for table `percentaging`
--
ALTER TABLE `percentaging`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `referral_bonus`
--
ALTER TABLE `referral_bonus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT for table `request_history`
--
ALTER TABLE `request_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `support`
--
ALTER TABLE `support`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `testimonies`
--
ALTER TABLE `testimonies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `login_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `website_settings`
--
ALTER TABLE `website_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
