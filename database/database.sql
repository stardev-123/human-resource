-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: *.hosting.stackcp.net
-- Generation Time: Dec 04, 2018 at 01:23 PM
-- Server version: 10.1.35-MariaDB
-- PHP Version: 5.6.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `REMEMBER SETUP A DATABASE THROUGH THE HOSTING AND CHANGE env FILE PARAMETERS`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `login_as_user_id` int(11) DEFAULT NULL,
  `user_agent` text COLLATE utf8_unicode_ci,
  `module` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `sub_module` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sub_module_id` int(11) DEFAULT NULL,
  `activity` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `audience` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_archived` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcement_designation`
--

CREATE TABLE `announcement_designation` (
  `id` int(11) NOT NULL,
  `announcement_id` int(11) DEFAULT NULL,
  `designation_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcement_user`
--

CREATE TABLE `announcement_user` (
  `id` int(11) NOT NULL,
  `announcement_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awards`
--

CREATE TABLE `awards` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `award_category_id` int(11) DEFAULT NULL,
  `date_of_award` date DEFAULT NULL,
  `duration` enum('monthly','yearly','period') COLLATE utf8_unicode_ci DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `month` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `year` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `is_archived` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `award_categories`
--

CREATE TABLE `award_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `award_user`
--

CREATE TABLE `award_user` (
  `id` int(11) NOT NULL,
  `award_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `backups`
--

CREATE TABLE `backups` (
  `id` int(11) NOT NULL,
  `file` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bulk_uploads`
--

CREATE TABLE `bulk_uploads` (
  `id` int(11) NOT NULL,
  `uuid` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `module` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `total` int(11) NOT NULL DEFAULT '0',
  `uploaded` int(11) NOT NULL DEFAULT '0',
  `rejected` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `avatar` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_line_1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_line_2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zipcode` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country_id` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `note` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_documents`
--

CREATE TABLE `client_documents` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `is_locked` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clocks`
--

CREATE TABLE `clocks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `clock_in` timestamp NULL DEFAULT NULL,
  `clock_out` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `value` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id`, `name`, `value`) VALUES
(1, 'company_name', ''),
(2, 'contact_person', ''),
(3, 'company_email', ''),
(4, 'company_phone', ''),
(5, 'company_website', ''),
(6, 'company_address_line_1', ''),
(7, 'company_address_line_2', ''),
(8, 'company_city', ''),
(9, 'company_state', ''),
(10, 'company_zipcode', ''),
(11, 'company_country_id', '243'),
(12, 'theme_color', 'blue'),
(13, 'theme_font', 'Lato'),
(14, 'direction', 'ltr'),
(15, 'application_name', ''),
(16, 'timezone_id', '350'),
(17, 'default_localization', 'en'),
(18, 'date_format', 'd-m-Y'),
(19, 'credit', 'Powered by GEMS'),
(20, 'notification_position', 'toast-bottom-right'),
(21, 'setup_guide', '1'),
(22, 'error_display', '1'),
(23, 'multilingual', '1'),
(24, 'enable_ip_filter', '1'),
(25, 'enable_activity_log', '1'),
(26, 'enable_message', '1'),
(27, 'enable_custom_field', '1'),
(28, 'enable_group_chat', '1'),
(29, 'enable_chat_refresh', '1'),
(30, 'chat_refresh_duration', '60'),
(31, 'under_maintenance_message', 'The system is under maintenance.'),
(32, 'time_format', '0'),
(33, 'enable_email_log', '0'),
(34, 'enable_email_template', '0'),
(35, 'enable_to_do', '1'),
(36, 'enable_backup', '1'),
(37, 'maintenance_mode', '0'),
(38, 'driver', 'log'),
(39, 'from_address', ''),
(40, 'from_name', ''),
(41, 'host', ''),
(42, 'port', ''),
(43, 'username', ''),
(44, 'password', ''),
(45, 'encryption', 'tls'),
(46, 'mandrill_secret', ''),
(47, 'mailgun_domain', ''),
(48, 'mailgun_secret', ''),
(49, 'mailgun_host', ''),
(50, 'mailgun_port', ''),
(51, 'mailgun_username', ''),
(52, 'mailgun_password', ''),
(53, 'session_lifetime', ''),
(54, 'reset_token_lifetime', ''),
(55, 'lock_screen_timeout', '1'),
(56, 'throttle_attempt', '5'),
(57, 'throttle_lockout_period', '2'),
(58, 'enable_login_as_user', '1'),
(59, 'login_type', 'username_or_email'),
(60, 'enable_user_registration', '0'),
(61, 'enable_password_strength_meter', '1'),
(62, 'enable_remember_me', '1'),
(63, 'enable_reset_password', '1'),
(64, 'recaptcha_key', ''),
(65, 'recaptcha_secret', ''),
(66, 'enable_two_factor_auth', '0'),
(67, 'two_factor_auth_type', '0'),
(68, 'enable_lock_screen', '0'),
(69, 'enable_throttle', '0'),
(70, 'enable_email_verification', '0'),
(71, 'enable_account_approval', '0'),
(72, 'enable_tnc', '0'),
(73, 'session_expire_browser_close', '0'),
(74, 'enable_recaptcha', '0'),
(75, 'enable_recaptcha_login', '0'),
(76, 'enable_recaptcha_registration', '0'),
(77, 'enable_recaptcha_reset_password', '0'),
(78, 'designation_level', '1'),
(79, 'location_level', '1'),
(80, 'user_manage_own_contact', '1'),
(81, 'user_manage_own_bank_account', '0'),
(82, 'user_manage_own_document', '1'),
(83, 'user_manage_own_qualification', '1'),
(84, 'user_manage_own_experience', '1'),
(85, 'list_user_criteria', 'active'),
(86, 'company_logo', ''),
(87, 'payroll_include_day_summary', '1'),
(88, 'payroll_include_hour_summary', '1'),
(89, 'payroll_include_leave_summary', '1'),
(90, 'enable_attendance_auto_clock', '0'),
(91, 'enable_attendance_auto_lock', '0'),
(92, 'attendance_auto_lock_days', '1'),
(93, 'leave_approval_level', 'single'),
(94, 'leave_no_of_level', ''),
(95, 'leave_approval_level_designation', ''),
(96, 'max_file_size_upload', '25');

-- --------------------------------------------------------

--
-- Table structure for table `contract_types`
--

CREATE TABLE `contract_types` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `symbol` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `position` enum('prefix','suffix') COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_default` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_fields`
--

CREATE TABLE `custom_fields` (
  `id` int(11) NOT NULL,
  `form` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `options` text COLLATE utf8_unicode_ci,
  `is_required` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_field_values`
--

CREATE TABLE `custom_field_values` (
  `id` int(11) NOT NULL,
  `unique_id` int(11) DEFAULT NULL,
  `custom_field_id` int(11) DEFAULT NULL,
  `value` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_reports`
--

CREATE TABLE `daily_reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `is_locked` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `is_hidden` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Insert some departments
--
INSERT INTO `departments` (`id`, `name`, `description`, `is_hidden`, `created_at`, `updated_at`) VALUES
(1, 'System Administration', NULL, 1, '2017-04-16 08:15:09', NULL),
(2, 'HR', 'Responsible for ensuring the engagement and well being of all employees', 0, '2017-04-17 03:22:45', '2017-04-17 03:22:45'),
(3, 'Finance', 'Responsible for the financial reporting within the business', 0, '2017-04-17 03:23:25', '2017-04-17 03:23:25'),
(4, 'Operations', 'Responsible for the day to day running of the business', 0, '2017-04-17 03:23:57', '2017-04-17 03:23:57'),
(5, 'Marketing', 'Responsible for maintaining the brand and marketing of the business', 0, '2017-04-17 03:24:47', '2017-04-17 03:24:47'),
(6, 'Sales', 'Responsible for aquiring and taking care of our customers', 0, '2017-04-17 03:25:38', '2018-01-04 17:36:18');

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

CREATE TABLE `designations` (
  `id` int(11) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `top_designation_id` int(11) DEFAULT NULL,
  `is_hidden` int(11) NOT NULL DEFAULT '0',
  `is_default` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `designations`
--

INSERT INTO `designations` (`id`, `department_id`, `name`, `description`, `top_designation_id`, `is_hidden`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 1, 'System Administrator', NULL, NULL, 1, 0, '2017-04-16 08:15:09', NULL),
(2, 4, 'Manager', NULL, NULL, 0, 0, '2017-04-16 08:15:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `document_types`
--

CREATE TABLE `document_types` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `education_levels`
--

CREATE TABLE `education_levels` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

CREATE TABLE `emails` (
  `id` int(11) NOT NULL,
  `to_address` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `from_address` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subject` text COLLATE utf8_unicode_ci,
  `body` text COLLATE utf8_unicode_ci,
  `attachments` text COLLATE utf8_unicode_ci,
  `module` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `uuid` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `expense_head_id` int(11) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `amount` decimal(25,0) NOT NULL DEFAULT '0',
  `date_of_expense` date DEFAULT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `is_archived` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_heads`
--

CREATE TABLE `expense_heads` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_status_details`
--

CREATE TABLE `expense_status_details` (
  `id` int(11) NOT NULL,
  `expense_id` int(11) DEFAULT NULL,
  `designation_id` int(11) DEFAULT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remarks` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `holidays`
--

CREATE TABLE `holidays` (
  `id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ip_filters`
--

CREATE TABLE `ip_filters` (
  `ip` int(11) NOT NULL,
  `start` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `end` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `uuid` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contract_type_id` int(11) DEFAULT NULL,
  `date_of_closing` date DEFAULT NULL,
  `no_of_post` int(11) NOT NULL DEFAULT '0',
  `publish_portal` int(11) NOT NULL DEFAULT '0',
  `location_id` int(11) DEFAULT NULL,
  `designation_id` int(11) DEFAULT NULL,
  `age_info` int(11) NOT NULL DEFAULT '0',
  `start_age` int(11) NOT NULL DEFAULT '0',
  `end_age` int(11) NOT NULL DEFAULT '0',
  `salary_info` int(11) NOT NULL DEFAULT '0',
  `currency_id` int(11) DEFAULT NULL,
  `start_salary` int(11) NOT NULL DEFAULT '0',
  `end_salary` int(11) NOT NULL DEFAULT '0',
  `qualification` text COLLATE utf8_unicode_ci,
  `experience` text COLLATE utf8_unicode_ci,
  `description` text COLLATE utf8_unicode_ci,
  `user_id` int(11) DEFAULT NULL,
  `is_archived` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_applications`
--

CREATE TABLE `job_applications` (
  `id` int(11) NOT NULL,
  `job_id` int(11) DEFAULT NULL,
  `first_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `primary_contact_number` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `secondary_contact_number` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_line_1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_line_2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zipcode` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country_id` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `additional_information` text COLLATE utf8_unicode_ci,
  `status` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_of_application` date DEFAULT NULL,
  `applicant_user_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_application_status_details`
--

CREATE TABLE `job_application_status_details` (
  `id` int(11) NOT NULL,
  `job_application_id` int(11) DEFAULT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remarks` text COLLATE utf8_unicode_ci,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leaves`
--

CREATE TABLE `leaves` (
  `id` int(11) NOT NULL,
  `uuid` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `leave_type_id` int(11) DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `date_approved` text COLLATE utf8_unicode_ci,
  `status` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_archived` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_status_details`
--

CREATE TABLE `leave_status_details` (
  `id` int(11) NOT NULL,
  `leave_id` int(11) DEFAULT NULL,
  `designation_id` int(11) DEFAULT NULL,
  `date_approved` text COLLATE utf8_unicode_ci,
  `status` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remarks` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_types`
--

CREATE TABLE `leave_types` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_half_day` int(11) NOT NULL DEFAULT '0',
  `description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `leave_types`
--

INSERT INTO `leave_types` (`id`, `name`, `is_half_day`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Annual Leave', 0, 'Allocated annual leave', '2018-01-08 21:07:02', '2018-01-08 21:45:10'),
(2, 'Sick', 0, 'Leave for sickness. After 3 days you must have a Doctors note.', '2018-01-08 21:08:06', '2018-01-08 21:08:06'),
(3, 'Parental Leave', 0, 'Leave to take care of children', '2018-01-08 21:08:42', '2018-01-08 21:08:42'),
(4, 'Dependants Leave', 0, 'Leave to take care of dependants', '2018-01-08 21:09:10', '2018-01-08 21:09:10');

-- --------------------------------------------------------

--
-- Table structure for table `librarys`
--

CREATE TABLE `librarys` (
  `id` int(11) NOT NULL,
  `title` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `audience2` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_archived` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `library_designation`
--

CREATE TABLE `library_designation` (
  `id` int(11) NOT NULL,
  `library_id` int(11) DEFAULT NULL,
  `designation_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `library_user`
--

CREATE TABLE `library_user` (
  `id` int(11) NOT NULL,
  `library_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `top_location_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_line_1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_line_2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zipcode` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `visible` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `name`, `order`, `visible`) VALUES
(1, 'home', 1, 1),
(2, 'user', 2, 1),
(3, 'client', 3, 1),
(4, 'attendance', 4, 1),
(5, 'leave', 5, 1),
(6, 'payroll', 6, 1),
(7, 'library', 7, 1),
(8, 'daily_report', 8, 1),
(9, 'announcement', 9, 1),
(10, 'expense', 10, 1),
(11, 'task', 11, 1),
(12, 'ticket', 12, 1),
(13, 'message', 13, 1),
(14, 'job', 14, 1),
(15, 'award', 15, 1),
(16, 'holiday', 16, 1);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `uuid` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_draft` int(11) NOT NULL DEFAULT '0',
  `from_user_id` int(11) DEFAULT NULL,
  `to_user_id` int(11) DEFAULT NULL,
  `subject` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8_unicode_ci,
  `is_starred_sender` int(11) NOT NULL DEFAULT '0',
  `is_starred_receiver` int(11) NOT NULL DEFAULT '0',
  `is_read` int(11) NOT NULL DEFAULT '0',
  `delete_sender` int(11) NOT NULL DEFAULT '0',
  `delete_receiver` int(11) NOT NULL DEFAULT '0',
  `reply_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `uuid` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `module` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user` text COLLATE utf8_unicode_ci,
  `user_read` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payrolls`
--

CREATE TABLE `payrolls` (
  `id` int(11) NOT NULL,
  `uuid` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `date_of_payroll` date DEFAULT NULL,
  `is_hourly` int(11) NOT NULL DEFAULT '0',
  `hourly` decimal(25,5) NOT NULL DEFAULT '0.00000',
  `late` decimal(25,5) NOT NULL DEFAULT '0.00000',
  `early_leaving` decimal(25,5) NOT NULL DEFAULT '0.00000',
  `overtime` decimal(25,5) NOT NULL DEFAULT '0.00000',
  `is_archived` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_details`
--

CREATE TABLE `payroll_details` (
  `id` int(11) NOT NULL,
  `payroll_id` int(11) DEFAULT NULL,
  `salary_head_id` int(11) DEFAULT NULL,
  `amount` decimal(25,5) NOT NULL DEFAULT '0.00000',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_default` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `category`, `description`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 'enable-login', 'login', NULL, 1, '2017-04-16 08:15:12', NULL),
(2, 'manage-configuration', 'configuration', NULL, 1, '2017-04-16 08:15:12', NULL),
(3, 'manage-localization', 'localization', NULL, 1, '2017-04-16 08:15:12', NULL),
(4, 'change-localization', 'localization', NULL, 1, '2017-04-16 08:15:12', NULL),
(5, 'manage-backup', 'backup', NULL, 1, '2017-04-16 08:15:12', NULL),
(6, 'manage-template', 'template', NULL, 1, '2017-04-16 08:15:12', NULL),
(7, 'manage-ip-filter', 'ip-filter', NULL, 1, '2017-04-16 08:15:12', NULL),
(8, 'manage-custom-field', 'custom-field', NULL, 1, '2017-04-16 08:15:12', NULL),
(9, 'manage-permission', 'permission', NULL, 1, '2017-04-16 08:15:12', NULL),
(10, 'list-designation', 'designation', NULL, 1, '2017-04-16 08:15:12', NULL),
(11, 'create-designation', 'designation', NULL, 1, '2017-04-16 08:15:12', NULL),
(12, 'edit-designation', 'designation', NULL, 1, '2017-04-16 08:15:12', NULL),
(13, 'delete-designation', 'designation', NULL, 1, '2017-04-16 08:15:12', NULL),
(14, 'manage-all-designation', 'designation', NULL, 1, '2017-04-16 08:15:12', NULL),
(15, 'manage-subordinate-designation', 'designation', NULL, 1, '2017-04-16 08:15:12', NULL),
(16, 'list-department', 'department', NULL, 1, '2017-04-16 08:15:12', NULL),
(17, 'create-department', 'department', NULL, 1, '2017-04-16 08:15:12', NULL),
(18, 'edit-department', 'department', NULL, 1, '2017-04-16 08:15:12', NULL),
(19, 'delete-department', 'department', NULL, 1, '2017-04-16 08:15:12', NULL),
(20, 'list-location', 'location', NULL, 1, '2017-04-16 08:15:12', NULL),
(21, 'create-location', 'location', NULL, 1, '2017-04-16 08:15:12', NULL),
(22, 'edit-location', 'location', NULL, 1, '2017-04-16 08:15:12', NULL),
(23, 'delete-location', 'location', NULL, 1, '2017-04-16 08:15:12', NULL),
(24, 'manage-all-location', 'location', NULL, 1, '2017-04-16 08:15:12', NULL),
(25, 'manage-subordinate-location', 'location', NULL, 1, '2017-04-16 08:15:12', NULL),
(26, 'manage-todo', 'to_do', NULL, 1, '2017-04-16 08:15:12', NULL),
(27, 'manage-role', 'role', NULL, 1, '2017-04-16 08:15:12', NULL),
(28, 'list-user', 'user', NULL, 1, '2017-04-16 08:15:12', NULL),
(29, 'create-user', 'user', NULL, 1, '2017-04-16 08:15:12', NULL),
(30, 'edit-user', 'user', NULL, 1, '2017-04-16 08:15:12', NULL),
(31, 'delete-user', 'user', NULL, 1, '2017-04-16 08:15:12', NULL),
(32, 'reset-user-password', 'user', NULL, 1, '2017-04-16 08:15:12', NULL),
(33, 'email-user', 'user', NULL, 1, '2017-04-16 08:15:12', NULL),
(34, 'change-user-status', 'user', NULL, 1, '2017-04-16 08:15:12', NULL),
(35, 'login-as-user', 'user', NULL, 1, '2017-04-16 08:15:12', NULL),
(36, 'manage-email-log', 'template', NULL, 1, '2017-04-16 08:15:12', NULL),
(37, 'manage-message', 'message', NULL, 1, '2017-04-16 08:15:12', NULL),
(38, 'list-announcement', 'announcement', NULL, 1, '2017-04-16 08:15:12', NULL),
(39, 'create-announcement', 'announcement', NULL, 1, '2017-04-16 08:15:12', NULL),
(40, 'edit-announcement', 'announcement', NULL, 1, '2017-04-16 08:15:12', NULL),
(41, 'delete-announcement', 'announcement', NULL, 1, '2017-04-16 08:15:12', NULL),
(42, 'manage-shift', 'shift', NULL, 1, '2017-04-16 08:15:12', NULL),
(43, 'list-task', 'task', NULL, 1, '2017-04-16 08:15:12', NULL),
(44, 'create-task', 'task', NULL, 1, '2017-04-16 08:15:12', NULL),
(45, 'edit-task', 'task', NULL, 1, '2017-04-16 08:15:12', NULL),
(46, 'delete-task', 'task', NULL, 1, '2017-04-16 08:15:12', NULL),
(47, 'list-daily-report', 'daily_report', NULL, 1, '2017-04-16 08:15:12', NULL),
(48, 'create-daily-report', 'daily_report', NULL, 1, '2017-04-16 08:15:12', NULL),
(49, 'edit-daily-report', 'daily_report', NULL, 1, '2017-04-16 08:15:12', NULL),
(50, 'delete-daily-report', 'daily_report', NULL, 1, '2017-04-16 08:15:12', NULL),
(51, 'manage-holiday', 'holiday', NULL, 1, '2017-04-16 08:15:12', NULL),
(52, 'list-expense', 'expense', NULL, 1, '2017-04-16 08:15:12', NULL),
(53, 'create-expense', 'expense', NULL, 1, '2017-04-16 08:15:12', NULL),
(54, 'edit-expense', 'expense', NULL, 1, '2017-04-16 08:15:12', NULL),
(55, 'delete-expense', 'expense', NULL, 1, '2017-04-16 08:15:12', NULL),
(56, 'list-leave', 'leave', NULL, 1, '2017-04-16 08:15:12', NULL),
(57, 'request-leave', 'leave', NULL, 1, '2017-04-16 08:15:12', NULL),
(58, 'edit-leave', 'leave', NULL, 1, '2017-04-16 08:15:12', NULL),
(59, 'delete-leave', 'leave', NULL, 1, '2017-04-16 08:15:12', NULL),
(60, 'manage-job', 'job', NULL, 1, '2017-04-16 08:15:12', NULL),
(61, 'list-ticket', 'ticket', NULL, 1, '2017-04-16 08:15:12', NULL),
(62, 'create-ticket', 'ticket', NULL, 1, '2017-04-16 08:15:12', NULL),
(63, 'delete-ticket', 'ticket', NULL, 1, '2017-04-16 08:15:12', NULL),
(64, 'list-award', 'award', NULL, 1, '2017-04-16 08:15:12', NULL),
(65, 'create-award', 'award', NULL, 1, '2017-04-16 08:15:12', NULL),
(66, 'edit-award', 'award', NULL, 1, '2017-04-16 08:15:12', NULL),
(67, 'delete-award', 'award', NULL, 1, '2017-04-16 08:15:12', NULL),
(68, 'edit-attendance', 'attendance', NULL, 1, '2017-04-16 08:15:12', NULL),
(69, 'upload-attendance', 'attendance', NULL, 1, '2017-04-16 08:15:12', NULL),
(70, 'create-payroll', 'payroll', NULL, 1, '2017-04-16 08:15:12', NULL),
(71, 'create-multiple-payroll', 'payroll', NULL, 1, '2017-04-16 08:15:12', NULL),
(72, 'edit-payroll', 'payroll', NULL, 1, '2017-04-16 08:15:12', NULL),
(73, 'delete-payroll', 'payroll', NULL, 1, '2017-04-16 08:15:12', NULL),
(74, 'list-library', 'library', NULL, 1, '2017-04-16 08:15:12', NULL),
(75, 'create-library', 'library', NULL, 1, '2017-04-16 08:15:12', NULL),
(76, 'edit-library', 'library', NULL, 1, '2017-04-16 08:15:12', NULL),
(77, 'delete-library', 'library', NULL, 1, '2017-04-16 08:15:12', NULL),
(78, 'list-client', 'client', NULL, 1, '2018-09-04 11:40:58', NULL),
(79, 'create-client', 'client', NULL, 1, '2018-09-04 11:40:58', NULL),
(80, 'edit-client', 'client', NULL, 1, '2018-09-04 11:40:58', NULL),
(81, 'delete-client', 'client', NULL, 1, '2018-09-04 11:40:58', NULL),
(82, 'email-client', 'client', NULL, 1, '2018-09-04 11:40:58', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(2, 1),
(3, 1),
(3, 3),
(3, 4),
(4, 1),
(4, 3),
(4, 4),
(5, 1),
(5, 3),
(5, 4),
(6, 1),
(6, 3),
(6, 4),
(7, 1),
(7, 3),
(7, 4),
(8, 1),
(9, 1),
(9, 3),
(9, 4),
(10, 1),
(10, 3),
(10, 4),
(11, 1),
(11, 3),
(11, 4),
(12, 1),
(12, 3),
(12, 4),
(13, 1),
(13, 3),
(13, 4),
(14, 1),
(14, 3),
(14, 4),
(15, 1),
(15, 3),
(15, 4),
(16, 1),
(16, 3),
(16, 4),
(17, 1),
(17, 3),
(17, 4),
(18, 1),
(18, 3),
(18, 4),
(19, 1),
(19, 3),
(19, 4),
(20, 1),
(20, 3),
(20, 4),
(21, 1),
(21, 3),
(21, 4),
(22, 1),
(22, 3),
(22, 4),
(23, 1),
(23, 3),
(23, 4),
(24, 1),
(24, 3),
(24, 4),
(25, 1),
(25, 3),
(25, 4),
(26, 1),
(26, 2),
(26, 3),
(26, 4),
(27, 1),
(27, 3),
(27, 4),
(28, 1),
(28, 2),
(28, 3),
(28, 4),
(29, 1),
(29, 4),
(30, 1),
(30, 4),
(31, 1),
(31, 4),
(32, 1),
(32, 4),
(33, 1),
(33, 2),
(33, 3),
(33, 4),
(34, 1),
(34, 4),
(35, 1),
(35, 4),
(36, 1),
(36, 3),
(36, 4),
(37, 1),
(37, 2),
(37, 3),
(37, 4),
(38, 1),
(38, 2),
(38, 3),
(38, 4),
(39, 1),
(39, 3),
(39, 4),
(40, 1),
(40, 3),
(40, 4),
(41, 1),
(41, 3),
(41, 4),
(42, 1),
(42, 2),
(42, 3),
(42, 4),
(43, 1),
(43, 2),
(43, 3),
(43, 4),
(44, 1),
(44, 2),
(44, 3),
(44, 4),
(45, 1),
(45, 3),
(45, 4),
(46, 1),
(46, 3),
(46, 4),
(47, 1),
(47, 3),
(47, 4),
(48, 1),
(48, 3),
(48, 4),
(49, 1),
(49, 4),
(50, 1),
(50, 4),
(51, 1),
(51, 2),
(51, 3),
(51, 4),
(52, 1),
(52, 2),
(52, 3),
(52, 4),
(53, 1),
(53, 2),
(53, 3),
(53, 4),
(54, 1),
(54, 3),
(54, 4),
(55, 1),
(55, 3),
(55, 4),
(56, 1),
(56, 2),
(56, 3),
(56, 4),
(57, 1),
(57, 2),
(57, 3),
(57, 4),
(58, 1),
(58, 3),
(58, 4),
(59, 1),
(59, 3),
(59, 4),
(60, 1),
(60, 2),
(60, 3),
(60, 4),
(61, 1),
(61, 2),
(61, 3),
(61, 4),
(62, 1),
(62, 2),
(62, 3),
(62, 4),
(63, 1),
(63, 3),
(63, 4),
(64, 1),
(64, 3),
(64, 4),
(65, 1),
(65, 3),
(65, 4),
(66, 1),
(66, 3),
(66, 4),
(67, 1),
(67, 3),
(67, 4),
(68, 1),
(69, 1),
(70, 1),
(70, 3),
(70, 4),
(71, 1),
(71, 3),
(71, 4),
(72, 1),
(72, 3),
(72, 4),
(73, 1),
(73, 3),
(73, 4),
(74, 1),
(74, 3),
(74, 4),
(75, 1),
(75, 3),
(75, 4),
(76, 1),
(76, 3),
(76, 4),
(77, 1),
(77, 3),
(77, 4),
(78, 1),
(78, 3),
(78, 4),
(79, 1),
(79, 3),
(79, 4),
(80, 1),
(80, 3),
(80, 4),
(81, 1),
(81, 3),
(81, 4),
(82, 1),
(82, 3),
(82, 4);

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `employee_code` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `avatar` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `date_of_anniversary` date DEFAULT NULL,
  `designation_id` int(11) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `gender` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nationality` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `marital_status` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unique_identification_number` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `work_phone` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `work_phone_extension` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `home_phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_line_1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_line_2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zipcode` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country_id` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `twitter` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `google_plus` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `github` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `first_name`, `last_name`, `employee_code`, `user_id`, `avatar`, `mobile`, `date_of_birth`, `date_of_anniversary`, `designation_id`, `location_id`, `gender`, `nationality`, `marital_status`, `unique_identification_number`, `phone`, `work_phone`, `work_phone_extension`, `home_phone`, `email`, `address_line_1`, `address_line_2`, `city`, `state`, `zipcode`, `country_id`, `facebook`, `twitter`, `google_plus`, `github`, `created_at`, `updated_at`) VALUES
(1, 'Admin', '', '1', 1, NULL, NULL, NULL, NULL, 1, NULL, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2017-04-16 08:15:09', '2018-10-12 11:29:56');

-- --------------------------------------------------------

--
-- Table structure for table `qualification_languages`
--

CREATE TABLE `qualification_languages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `qualification_skills`
--

CREATE TABLE `qualification_skills` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `q_failed_jobs`
--

CREATE TABLE `q_failed_jobs` (
  `id` int(10) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8_unicode_ci NOT NULL,
  `queue` text COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `q_jobs`
--

CREATE TABLE `q_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_hidden` int(11) NOT NULL DEFAULT '0',
  `is_default` int(11) NOT NULL DEFAULT '0',
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `is_hidden`, `is_default`, `description`, `created_at`, `updated_at`) VALUES
(1, 'admin', 1, 0, NULL, NULL, NULL),
(2, 'user', 0, 1, 'Able to use the features of the site, but not able to make changes to the site configuration', '2017-04-17 03:14:16', '2017-04-17 03:16:25'),
(3, 'manager', 0, 0, 'Able to use the features of the site. Able to manage subordinates.', '2017-04-17 03:15:44', '2017-04-17 03:16:25'),
(4, 'super user', 0, 0, 'Able to use the features of the site and to see all employees', '2017-04-17 03:16:16', '2017-04-17 03:16:25');

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`user_id`, `role_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `salary_heads`
--

CREATE TABLE `salary_heads` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `type` enum('earning','deduction') COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_fixed` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `setup`
--

CREATE TABLE `setup` (
  `id` int(11) NOT NULL,
  `module` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `completed` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `setup`
--

INSERT INTO `setup` (`id`, `module`, `completed`) VALUES
(1, 'installation', 1),
(2, 'general_configuration', 0),
(3, 'system_configuration', 0),
(4, 'mail', 0),
(5, 'menu', 0),
(6, 'role', 0),
(7, 'permission', 0),
(8, 'department', 0),
(9, 'designation', 0),
(10, 'location', 0);

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

CREATE TABLE `shifts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `is_default` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shift_details`
--

CREATE TABLE `shift_details` (
  `id` int(11) NOT NULL,
  `shift_id` int(11) DEFAULT NULL,
  `day` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `in_time` time DEFAULT NULL,
  `out_time` time DEFAULT NULL,
  `overnight` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `starred_tasks`
--

CREATE TABLE `starred_tasks` (
  `id` int(11) NOT NULL,
  `task_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_tasks`
--

CREATE TABLE `sub_tasks` (
  `id` int(11) NOT NULL,
  `task_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_task_ratings`
--

CREATE TABLE `sub_task_ratings` (
  `id` int(11) NOT NULL,
  `sub_task_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating` int(11) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `uuid` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `task_category_id` int(11) DEFAULT NULL,
  `task_priority_id` int(11) DEFAULT NULL,
  `title` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `start_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `complete_date` timestamp NULL DEFAULT NULL,
  `progress` int(11) DEFAULT '0',
  `tags` text COLLATE utf8_unicode_ci,
  `sub_task_rating` int(11) NOT NULL DEFAULT '0',
  `sign_off_status` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `is_archived` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_attachments`
--

CREATE TABLE `task_attachments` (
  `id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `task_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `attachments` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_categories`
--

CREATE TABLE `task_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_comments`
--

CREATE TABLE `task_comments` (
  `id` int(11) NOT NULL,
  `task_id` int(11) DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_notes`
--

CREATE TABLE `task_notes` (
  `id` int(11) NOT NULL,
  `task_id` int(11) DEFAULT NULL,
  `note` text COLLATE utf8_unicode_ci,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_priorities`
--

CREATE TABLE `task_priorities` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_signoff_requests`
--

CREATE TABLE `task_signoff_requests` (
  `id` int(11) NOT NULL,
  `task_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remarks` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_user`
--

CREATE TABLE `task_user` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `task_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE `templates` (
  `id` int(11) NOT NULL,
  `is_default` int(11) NOT NULL DEFAULT '0',
  `name` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subject` text COLLATE utf8_unicode_ci,
  `body` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `templates`
--

INSERT INTO `templates` (`id`, `is_default`, `name`, `slug`, `category`, `subject`, `body`, `created_at`, `updated_at`) VALUES
(1, 1, 'Welcome Email', 'welcome-email', 'user', 'Welcome Email | [COMPANY_NAME]', '<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n	<tbody>\r\n		<tr>\r\n			<td valign=\"top\">			\r\n				<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n					<tbody>\r\n						<tr>\r\n							<td height=\"90\" width=\"600\" align=\"right\">\r\n								<p>[COMPANY_LOGO]</p>\r\n								<p style=\"text-align:left;font-size:16px;\"><b>Template Header</b></p>\r\n							</td>\r\n						</tr>\r\n						<tr>\r\n							<td style=\"background-color:#f5f5f5;border:1px solid #e0e0e0;font-family:Helvetica, Arial, sans-serif;\" valign=\"top\" width=\"600\">						\r\n								<table align=\"center\">\r\n									<tbody>\r\n										<tr>\r\n											<td height=\"10\" width=\"560\">&nbsp;</td>\r\n										</tr>\r\n										<tr>\r\n											<td width=\"560\">\r\n												<p>Template Content goes here....\r\n\r\n												<br><p>Have a Good Day!!</p>\r\n											</td>\r\n										</tr>\r\n										<tr>\r\n											<td height=\"10\" width=\"560\"></td>\r\n										</tr>\r\n									</tbody>\r\n								</table>\r\n							</td>\r\n						</tr>\r\n						<tr><td height=\"10\" width=\"600\">&nbsp;</td></tr>\r\n						<tr><td align=\"right\">\r\n								<span style=\"font-size:12px;color:#999999;font-family:Helvetica, Arial, sans-serif;\">[COMPANY_NAME] <br>\r\n								[COMPANY_EMAIL] <br>\r\n								[COMPANY_PHONE] <br>\r\n								[COMPANY_WEBSITE] <br></span>\r\n							</td>\r\n						</tr>\r\n					</tbody>\r\n				</table>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>', '2017-04-16 08:15:12', NULL),
(2, 1, 'Birthday Email', 'birthday-email', 'user', 'Happy Birthday [NAME] | [COMPANY_NAME]', '<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tbody><tr><td valign=\"top\">			\r\n				<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tbody><tr><td height=\"90\" width=\"600\" align=\"right\">\r\n								<p>[COMPANY_LOGO]</p>\r\n								<p style=\"text-align:left;font-size:16px;\"><b>Happy Birthday</b></p>\r\n							</td>\r\n						</tr><tr><td style=\"background-color:#f5f5f5;border:1px solid #e0e0e0;font-family:Helvetica, Arial, sans-serif;\" valign=\"top\" width=\"600\">						\r\n								<table align=\"center\"><tbody><tr><td height=\"10\" width=\"560\">&nbsp;</td>\r\n										</tr><tr><td width=\"560\">\r\n												<p>Dear [NAME],</p><p>We wish you a Very Happy Birthday.</p><p>Have a Good Day!!</p>\r\n											</td>\r\n										</tr><tr><td height=\"10\" width=\"560\"></td>\r\n										</tr></tbody></table></td>\r\n						</tr><tr><td height=\"10\" width=\"600\">&nbsp;</td></tr><tr><td align=\"right\">\r\n								<span style=\"font-size:12px;color:#999999;font-family:Helvetica, Arial, sans-serif;\">[COMPANY_NAME] <br>\r\n								[COMPANY_EMAIL] <br>\r\n								[COMPANY_PHONE] <br>\r\n								[COMPANY_WEBSITE] <br></span>\r\n							</td>\r\n						</tr></tbody></table></td>\r\n		</tr></tbody></table>', '2017-04-16 08:15:12', NULL),
(3, 1, 'Anniversary Email', 'anniversary-email', 'user', 'Wish You a Very Happy Anniversary [NAME] | [COMPANY_NAME]', '<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tbody><tr><td valign=\"top\">			\r\n				<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tbody><tr><td height=\"90\" width=\"600\" align=\"right\">\r\n								<p>[COMPANY_LOGO]</p>\r\n								<p style=\"text-align:left;font-size:16px;\"><b>Happy Anniversary</b></p>\r\n							</td>\r\n						</tr><tr><td style=\"background-color:#f5f5f5;border:1px solid #e0e0e0;font-family:Helvetica, Arial, sans-serif;\" valign=\"top\" width=\"600\">						\r\n								<table align=\"center\"><tbody><tr><td height=\"10\" width=\"560\">&nbsp;</td>\r\n										</tr><tr><td width=\"560\">\r\n												<p>Dear [NAME],</p><p>We wish you a Very Happy Anniversary.</p><p>Have a Good Day!!</p>\r\n											</td>\r\n										</tr><tr><td height=\"10\" width=\"560\"></td>\r\n										</tr></tbody></table></td>\r\n						</tr><tr><td height=\"10\" width=\"600\">&nbsp;</td></tr><tr><td align=\"right\">\r\n								<span style=\"font-size:12px;color:#999999;font-family:Helvetica, Arial, sans-serif;\">[COMPANY_NAME] <br>\r\n								[COMPANY_EMAIL] <br>\r\n								[COMPANY_PHONE] <br>\r\n								[COMPANY_WEBSITE] <br></span>\r\n							</td>\r\n						</tr></tbody></table></td>\r\n		</tr></tbody></table>', '2017-04-16 08:15:12', NULL),
(4, 1, 'Payroll', 'payroll', 'payroll', 'Payroll Generated | [COMPANY_NAME]', '<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n	<tbody>\r\n		<tr>\r\n			<td valign=\"top\">			\r\n				<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n					<tbody>\r\n						<tr>\r\n							<td height=\"90\" width=\"600\" align=\"right\">\r\n								<p>[COMPANY_LOGO]</p>\r\n								<p style=\"text-align:left;font-size:16px;\"><b>Payroll Generated</b></p>\r\n							</td>\r\n						</tr>\r\n						<tr>\r\n							<td style=\"background-color:#f5f5f5;border:1px solid #e0e0e0;font-family:Helvetica, Arial, sans-serif;\" valign=\"top\" width=\"600\">						\r\n								<table align=\"center\">\r\n									<tbody>\r\n										<tr>\r\n											<td height=\"10\" width=\"560\">&nbsp;</td>\r\n										</tr>\r\n										<tr>\r\n											<td width=\"560\">\r\n												<p>Dear [NAME],\r\n\r\n												<br> Payroll from [PAYROLL_FROM_DATE] to [PAYROLL_TO_DATE] is generated on [DATE_OF_PAYROLL]. Please find the Payroll in the attachment.\r\n\r\n												<br><p>Have a Good Day!!</p>\r\n											</td>\r\n										</tr>\r\n										<tr>\r\n											<td height=\"10\" width=\"560\"></td>\r\n										</tr>\r\n									</tbody>\r\n								</table>\r\n							</td>\r\n						</tr>\r\n						<tr><td height=\"10\" width=\"600\">&nbsp;</td></tr>\r\n						<tr><td align=\"right\">\r\n								<span style=\"font-size:12px;color:#999999;font-family:Helvetica, Arial, sans-serif;\">[COMPANY_NAME] <br>\r\n								[COMPANY_EMAIL] <br>\r\n								[COMPANY_PHONE] <br>\r\n								[COMPANY_WEBSITE] <br></span>\r\n							</td>\r\n						</tr>\r\n					</tbody>\r\n				</table>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>', '2017-04-16 08:15:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `uuid` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `ticket_priority_id` int(11) DEFAULT NULL,
  `ticket_category_id` int(11) DEFAULT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_archived` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_categories`
--

CREATE TABLE `ticket_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ticket_categories`
--

INSERT INTO `ticket_categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Facilities Request', 'Report issues with the facilities - buildings, utilities, etc', '2018-01-08 21:20:45', '2018-01-08 21:20:45'),
(2, 'Equipment', 'Report issues with equipment', '2018-01-08 21:21:07', '2018-01-08 21:21:07'),
(3, 'IT', 'Report issues with computers and software', '2018-01-08 21:21:30', '2018-01-08 21:21:30'),
(4, 'Human Resources Grievance', 'Raise a grievance with a team member', '2018-01-08 21:24:57', '2018-01-08 21:24:57'),
(5, 'Innovation', 'Got a great idea - tell us about it right here.', '2018-01-08 21:25:32', '2018-01-08 21:25:32');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_priorities`
--

CREATE TABLE `ticket_priorities` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ticket_priorities`
--

INSERT INTO `ticket_priorities` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'High', 'Urgent - please deal with immediately', '2018-01-08 21:25:55', '2018-01-08 21:25:55'),
(2, 'Medium', 'Deal with in the next 5 working days', '2018-01-08 21:26:36', '2018-01-08 21:26:36'),
(3, 'Low', 'Deal with in the next 14 days', '2018-01-08 21:27:04', '2018-01-08 21:27:04'),
(4, 'FYI', 'For information only', '2018-01-08 21:27:21', '2018-01-08 21:27:21');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_replies`
--

CREATE TABLE `ticket_replies` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `todos`
--

CREATE TABLE `todos` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `visibility` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` text COLLATE utf8_unicode_ci,
  `description` text COLLATE utf8_unicode_ci,
  `date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE `uploads` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `module` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `upload_key` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_filename` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `attachments` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_temp_delete` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `auth_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `provider` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `provider_unique_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_hidden` int(11) NOT NULL DEFAULT '0',
  `is_default` int(11) NOT NULL DEFAULT '0',
  `disable_login` int(11) NOT NULL DEFAULT '0',
  `last_login` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_login_now` timestamp NULL DEFAULT NULL,
  `last_login_ip_now` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `remember_token`, `activation_token`, `auth_token`, `status`, `provider`, `provider_unique_id`, `is_hidden`, `is_default`, `disable_login`, `last_login`, `last_login_ip`, `last_login_now`, `last_login_ip_now`, `created_at`, `updated_at`) VALUES
(1, 'admingems', '', '$2y$10$ERyVH3yw0lrxEtTKiKNcVezLD6PcX92Bq.8wlwhwhQHJsGrF5jqYS', '', NULL, '', 'active', NULL, NULL, 1, 0, 0, '2018-11-30 13:53:46', '92.27.102.177', '2018-12-03 11:03:27', '90.205.212.151', '2017-04-16 08:15:09', '2018-12-03 11:03:27');

-- --------------------------------------------------------

--
-- Table structure for table `user_bank_accounts`
--

CREATE TABLE `user_bank_accounts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `is_primary` int(11) NOT NULL DEFAULT '0',
  `is_locked` int(11) NOT NULL DEFAULT '0',
  `bank_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `account_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `account_number` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bank_code` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bank_branch` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_contacts`
--

CREATE TABLE `user_contacts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `is_primary` int(11) NOT NULL DEFAULT '0',
  `is_locked` int(11) NOT NULL DEFAULT '0',
  `is_dependent` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `relation` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `work_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `personal_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `work_phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `work_phone_extension` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `home` varchar(29) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_line_1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_line_2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zipcode` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_contracts`
--

CREATE TABLE `user_contracts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `contract_type_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_designations`
--

CREATE TABLE `user_designations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `designation_id` int(11) DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_designations`
--

INSERT INTO `user_designations` (`id`, `user_id`, `designation_id`, `from_date`, `to_date`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL, NULL, '2018-08-01 23:09:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_documents`
--

CREATE TABLE `user_documents` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `is_locked` int(11) NOT NULL DEFAULT '0',
  `document_type_id` int(11) DEFAULT NULL,
  `date_of_expiry` date DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_employments`
--

CREATE TABLE `user_employments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date_of_joining` date DEFAULT NULL,
  `date_of_leaving` date DEFAULT NULL,
  `leaving_remarks` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_experiences`
--

CREATE TABLE `user_experiences` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `is_locked` int(11) NOT NULL DEFAULT '0',
  `company_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_contact_number` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_website` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `job_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_leaves`
--

CREATE TABLE `user_leaves` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_leave_details`
--

CREATE TABLE `user_leave_details` (
  `id` int(11) NOT NULL,
  `user_leave_id` int(11) DEFAULT NULL,
  `leave_type_id` int(11) DEFAULT NULL,
  `leave_assigned` int(11) NOT NULL DEFAULT '0',
  `leave_used` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_locations`
--

CREATE TABLE `user_locations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_qualifications`
--

CREATE TABLE `user_qualifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `is_locked` int(11) NOT NULL DEFAULT '0',
  `institute_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `education_level_id` int(11) DEFAULT NULL,
  `qualification_language_id` int(11) DEFAULT NULL,
  `qualification_skill_id` int(11) DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_salaries`
--

CREATE TABLE `user_salaries` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `hourly_rate` decimal(25,0) NOT NULL DEFAULT '0',
  `overtime_hourly_rate` decimal(25,0) NOT NULL DEFAULT '0',
  `late_hourly_rate` decimal(25,0) NOT NULL DEFAULT '0',
  `early_leaving_hourly_rate` decimal(25,0) NOT NULL DEFAULT '0',
  `description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_salary_details`
--

CREATE TABLE `user_salary_details` (
  `id` int(11) NOT NULL,
  `user_salary_id` int(11) DEFAULT NULL,
  `salary_head_id` int(11) DEFAULT NULL,
  `amount` decimal(25,5) NOT NULL DEFAULT '0.00000',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_shifts`
--

CREATE TABLE `user_shifts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `shift_id` int(11) DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `in_time` time DEFAULT NULL,
  `out_time` time DEFAULT NULL,
  `overnight` int(11) NOT NULL DEFAULT '0',
  `description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `login_as_user_id` (`login_as_user_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `announcement_designation`
--
ALTER TABLE `announcement_designation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `announcement_id` (`announcement_id`),
  ADD KEY `designation_id` (`designation_id`);

--
-- Indexes for table `announcement_user`
--
ALTER TABLE `announcement_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `announcement_id` (`announcement_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `awards`
--
ALTER TABLE `awards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `award_category_id` (`award_category_id`);

--
-- Indexes for table `award_categories`
--
ALTER TABLE `award_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `award_user`
--
ALTER TABLE `award_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `award_id` (`award_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `backups`
--
ALTER TABLE `backups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bulk_uploads`
--
ALTER TABLE `bulk_uploads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_documents`
--
ALTER TABLE `client_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `clocks`
--
ALTER TABLE `clocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contract_types`
--
ALTER TABLE `contract_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custom_fields`
--
ALTER TABLE `custom_fields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custom_field_values`
--
ALTER TABLE `custom_field_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `custom_field_id` (`custom_field_id`);

--
-- Indexes for table `daily_reports`
--
ALTER TABLE `daily_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `designations`
--
ALTER TABLE `designations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `top_designation_id` (`top_designation_id`);

--
-- Indexes for table `document_types`
--
ALTER TABLE `document_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `education_levels`
--
ALTER TABLE `education_levels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emails`
--
ALTER TABLE `emails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `expense_head_id` (`expense_head_id`),
  ADD KEY `currency_id` (`currency_id`);

--
-- Indexes for table `expense_heads`
--
ALTER TABLE `expense_heads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense_status_details`
--
ALTER TABLE `expense_status_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expense_id` (`expense_id`),
  ADD KEY `designation_id` (`designation_id`);

--
-- Indexes for table `holidays`
--
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ip_filters`
--
ALTER TABLE `ip_filters`
  ADD PRIMARY KEY (`ip`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contract_type_id` (`contract_type_id`),
  ADD KEY `location_id` (`location_id`),
  ADD KEY `designation_id` (`designation_id`),
  ADD KEY `currency_id` (`currency_id`),
  ADD KEY `user_Id` (`user_id`);

--
-- Indexes for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `applicant_user_id` (`applicant_user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `job_application_status_details`
--
ALTER TABLE `job_application_status_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_application_id` (`job_application_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `leaves`
--
ALTER TABLE `leaves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `leave_type_id` (`leave_type_id`);

--
-- Indexes for table `leave_status_details`
--
ALTER TABLE `leave_status_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leave_id` (`leave_id`),
  ADD KEY `designation_id` (`designation_id`);

--
-- Indexes for table `leave_types`
--
ALTER TABLE `leave_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `librarys`
--
ALTER TABLE `librarys`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `library_designation`
--
ALTER TABLE `library_designation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `library_id` (`library_id`),
  ADD KEY `designation_id` (`designation_id`);

--
-- Indexes for table `library_user`
--
ALTER TABLE `library_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `library_id` (`library_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `top_location_id` (`top_location_id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`uuid`),
  ADD KEY `from_user_id` (`from_user_id`),
  ADD KEY `to_user_id` (`to_user_id`),
  ADD KEY `reply_id` (`reply_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

--
-- Indexes for table `payrolls`
--
ALTER TABLE `payrolls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `currency_id` (`currency_id`);

--
-- Indexes for table `payroll_details`
--
ALTER TABLE `payroll_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payroll_id` (`payroll_id`),
  ADD KEY `salary_head_id` (`salary_head_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `permission_role_role_id_foreign` (`role_id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `designation_id` (`designation_id`),
  ADD KEY `location_id` (`location_id`),
  ADD KEY `profiles_email_foreign` (`email`);

--
-- Indexes for table `qualification_languages`
--
ALTER TABLE `qualification_languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qualification_skills`
--
ALTER TABLE `qualification_skills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `q_failed_jobs`
--
ALTER TABLE `q_failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `q_jobs`
--
ALTER TABLE `q_jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_reserved_reserved_at_index` (`queue`,`reserved`,`reserved_at`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `role_user_role_id_foreign` (`role_id`);

--
-- Indexes for table `salary_heads`
--
ALTER TABLE `salary_heads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `setup`
--
ALTER TABLE `setup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shift_details`
--
ALTER TABLE `shift_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shift_id` (`shift_id`);

--
-- Indexes for table `sub_tasks`
--
ALTER TABLE `sub_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `sub_task_ratings`
--
ALTER TABLE `sub_task_ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sub_task_id` (`sub_task_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_category_id` (`task_category_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `task_priority_id` (`task_priority_id`);

--
-- Indexes for table `task_attachments`
--
ALTER TABLE `task_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `task_id` (`task_id`);

--
-- Indexes for table `task_categories`
--
ALTER TABLE `task_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_comments`
--
ALTER TABLE `task_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `task_notes`
--
ALTER TABLE `task_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `task_priorities`
--
ALTER TABLE `task_priorities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_signoff_requests`
--
ALTER TABLE `task_signoff_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `task_user`
--
ALTER TABLE `task_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `templates`
--
ALTER TABLE `templates`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `ticket_priority_id` (`ticket_priority_id`),
  ADD KEY `ticket_category_id` (`ticket_category_id`);

--
-- Indexes for table `ticket_categories`
--
ALTER TABLE `ticket_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_priorities`
--
ALTER TABLE `ticket_priorities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_id` (`ticket_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `todos`
--
ALTER TABLE `todos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `uploads`
--
ALTER TABLE `uploads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `user_bank_accounts`
--
ALTER TABLE `user_bank_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_contacts`
--
ALTER TABLE `user_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_contracts`
--
ALTER TABLE `user_contracts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `contract_type_id` (`contract_type_id`);

--
-- Indexes for table `user_designations`
--
ALTER TABLE `user_designations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `designation_id` (`designation_id`);

--
-- Indexes for table `user_documents`
--
ALTER TABLE `user_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `document_type_id` (`document_type_id`);

--
-- Indexes for table `user_employments`
--
ALTER TABLE `user_employments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_experiences`
--
ALTER TABLE `user_experiences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_leaves`
--
ALTER TABLE `user_leaves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_leave_details`
--
ALTER TABLE `user_leave_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_leave_id` (`user_leave_id`),
  ADD KEY `leave_type_id` (`leave_type_id`);

--
-- Indexes for table `user_locations`
--
ALTER TABLE `user_locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `user_qualifications`
--
ALTER TABLE `user_qualifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `education_level_id` (`education_level_id`),
  ADD KEY `qualification_language_id` (`qualification_language_id`),
  ADD KEY `qualification_skill_id` (`qualification_skill_id`);

--
-- Indexes for table `user_salaries`
--
ALTER TABLE `user_salaries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `currency_id` (`currency_id`);

--
-- Indexes for table `user_salary_details`
--
ALTER TABLE `user_salary_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_salary_id` (`user_salary_id`),
  ADD KEY `salary_head_id` (`salary_head_id`);

--
-- Indexes for table `user_shifts`
--
ALTER TABLE `user_shifts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `shift_id` (`shift_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announcement_designation`
--
ALTER TABLE `announcement_designation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announcement_user`
--
ALTER TABLE `announcement_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `awards`
--
ALTER TABLE `awards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `award_categories`
--
ALTER TABLE `award_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `award_user`
--
ALTER TABLE `award_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `backups`
--
ALTER TABLE `backups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bulk_uploads`
--
ALTER TABLE `bulk_uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_documents`
--
ALTER TABLE `client_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clocks`
--
ALTER TABLE `clocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `contract_types`
--
ALTER TABLE `contract_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_fields`
--
ALTER TABLE `custom_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_field_values`
--
ALTER TABLE `custom_field_values`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_reports`
--
ALTER TABLE `daily_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `designations`
--
ALTER TABLE `designations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `document_types`
--
ALTER TABLE `document_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `education_levels`
--
ALTER TABLE `education_levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emails`
--
ALTER TABLE `emails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_heads`
--
ALTER TABLE `expense_heads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_status_details`
--
ALTER TABLE `expense_status_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `holidays`
--
ALTER TABLE `holidays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ip_filters`
--
ALTER TABLE `ip_filters`
  MODIFY `ip` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_applications`
--
ALTER TABLE `job_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_application_status_details`
--
ALTER TABLE `job_application_status_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leaves`
--
ALTER TABLE `leaves`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_status_details`
--
ALTER TABLE `leave_status_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_types`
--
ALTER TABLE `leave_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `librarys`
--
ALTER TABLE `librarys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `library_designation`
--
ALTER TABLE `library_designation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `library_user`
--
ALTER TABLE `library_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payrolls`
--
ALTER TABLE `payrolls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll_details`
--
ALTER TABLE `payroll_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `qualification_languages`
--
ALTER TABLE `qualification_languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `qualification_skills`
--
ALTER TABLE `qualification_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `q_failed_jobs`
--
ALTER TABLE `q_failed_jobs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `q_jobs`
--
ALTER TABLE `q_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `salary_heads`
--
ALTER TABLE `salary_heads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `setup`
--
ALTER TABLE `setup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shift_details`
--
ALTER TABLE `shift_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sub_tasks`
--
ALTER TABLE `sub_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sub_task_ratings`
--
ALTER TABLE `sub_task_ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_attachments`
--
ALTER TABLE `task_attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_categories`
--
ALTER TABLE `task_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_comments`
--
ALTER TABLE `task_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_notes`
--
ALTER TABLE `task_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_priorities`
--
ALTER TABLE `task_priorities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_signoff_requests`
--
ALTER TABLE `task_signoff_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_user`
--
ALTER TABLE `task_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `templates`
--
ALTER TABLE `templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_categories`
--
ALTER TABLE `ticket_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ticket_priorities`
--
ALTER TABLE `ticket_priorities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `todos`
--
ALTER TABLE `todos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `user_bank_accounts`
--
ALTER TABLE `user_bank_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_contacts`
--
ALTER TABLE `user_contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_contracts`
--
ALTER TABLE `user_contracts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_designations`
--
ALTER TABLE `user_designations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `user_documents`
--
ALTER TABLE `user_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_employments`
--
ALTER TABLE `user_employments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_experiences`
--
ALTER TABLE `user_experiences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_leaves`
--
ALTER TABLE `user_leaves`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_leave_details`
--
ALTER TABLE `user_leave_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_locations`
--
ALTER TABLE `user_locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_qualifications`
--
ALTER TABLE `user_qualifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_salaries`
--
ALTER TABLE `user_salaries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_salary_details`
--
ALTER TABLE `user_salary_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_shifts`
--
ALTER TABLE `user_shifts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_login_as_user_id_foreign` FOREIGN KEY (`login_as_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `announcement_designation`
--
ALTER TABLE `announcement_designation`
  ADD CONSTRAINT `announcement_designation_announcement_id_foreign` FOREIGN KEY (`announcement_id`) REFERENCES `announcements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `announcement_designation_designation_id_foreign` FOREIGN KEY (`designation_id`) REFERENCES `designations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `announcement_user`
--
ALTER TABLE `announcement_user`
  ADD CONSTRAINT `announcement_user_announcement_id_foreign` FOREIGN KEY (`announcement_id`) REFERENCES `announcements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `announcement_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `awards`
--
ALTER TABLE `awards`
  ADD CONSTRAINT `awards_award_category_id_foreign` FOREIGN KEY (`award_category_id`) REFERENCES `award_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `awards_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `award_user`
--
ALTER TABLE `award_user`
  ADD CONSTRAINT `award_user_award_id_foreign` FOREIGN KEY (`award_id`) REFERENCES `awards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `award_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bulk_uploads`
--
ALTER TABLE `bulk_uploads`
  ADD CONSTRAINT `bulk_uploads_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `chat_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `client_documents`
--
ALTER TABLE `client_documents`
  ADD CONSTRAINT `client_documents_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `clocks`
--
ALTER TABLE `clocks`
  ADD CONSTRAINT `clocks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `custom_field_values`
--
ALTER TABLE `custom_field_values`
  ADD CONSTRAINT `custom_field_values_custom_field_id_foreign` FOREIGN KEY (`custom_field_id`) REFERENCES `custom_fields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `daily_reports`
--
ALTER TABLE `daily_reports`
  ADD CONSTRAINT `daily_reports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `designations`
--
ALTER TABLE `designations`
  ADD CONSTRAINT `designations_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `designations_top_designation_id_foreign` FOREIGN KEY (`top_designation_id`) REFERENCES `designations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `expenses_expense_head_id_foreign` FOREIGN KEY (`expense_head_id`) REFERENCES `expense_heads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `expenses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `expense_status_details`
--
ALTER TABLE `expense_status_details`
  ADD CONSTRAINT `esd_designation_id_foreign` FOREIGN KEY (`designation_id`) REFERENCES `designations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `esd_expense_id_foreign` FOREIGN KEY (`expense_id`) REFERENCES `expenses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_contract_type_id_foreign` FOREIGN KEY (`contract_type_id`) REFERENCES `contract_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jobs_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jobs_designation_id_foreign` FOREIGN KEY (`designation_id`) REFERENCES `designations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jobs_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jobs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `leaves`
--
ALTER TABLE `leaves`
  ADD CONSTRAINT `leaves_leave_type_id_foreign` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `leaves_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `leave_status_details`
--
ALTER TABLE `leave_status_details`
  ADD CONSTRAINT `lsd_designation_id_foreign` FOREIGN KEY (`designation_id`) REFERENCES `designations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lsd_leave_id_foreign` FOREIGN KEY (`leave_id`) REFERENCES `leaves` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `librarys`
--
ALTER TABLE `librarys`
  ADD CONSTRAINT `librarys_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `library_designation`
--
ALTER TABLE `library_designation`
  ADD CONSTRAINT `library_designation_designation_id_foreign` FOREIGN KEY (`designation_id`) REFERENCES `designations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `library_designation_library_id_foreign` FOREIGN KEY (`library_id`) REFERENCES `librarys` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `library_user`
--
ALTER TABLE `library_user`
  ADD CONSTRAINT `library_user_library_id_foreign` FOREIGN KEY (`library_id`) REFERENCES `librarys` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `library_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `locations`
--
ALTER TABLE `locations`
  ADD CONSTRAINT `locations_top_location_id_foreign` FOREIGN KEY (`top_location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_from_user_id_foreign` FOREIGN KEY (`from_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `messages_reply_id_foreign` FOREIGN KEY (`reply_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `messages_to_user_id_foreign` FOREIGN KEY (`to_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payrolls`
--
ALTER TABLE `payrolls`
  ADD CONSTRAINT `payrolls_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payrolls_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payroll_details`
--
ALTER TABLE `payroll_details`
  ADD CONSTRAINT `payroll_details_payroll_id_foreign` FOREIGN KEY (`payroll_id`) REFERENCES `payrolls` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payroll_details_salary_head_id_foreign` FOREIGN KEY (`salary_head_id`) REFERENCES `salary_heads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`),
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `profiles_designation_id_foreign` FOREIGN KEY (`designation_id`) REFERENCES `designations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `profiles_email_foreign` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `profiles_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `shift_details`
--
ALTER TABLE `shift_details`
  ADD CONSTRAINT `shift_details_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sub_tasks`
--
ALTER TABLE `sub_tasks`
  ADD CONSTRAINT `sub_tasks_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sub_tasks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sub_task_ratings`
--
ALTER TABLE `sub_task_ratings`
  ADD CONSTRAINT `sub_task_ratings_sub_task_id_foreign` FOREIGN KEY (`sub_task_id`) REFERENCES `sub_tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sub_task_ratings_sub_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `task_task_priority_id_foreign` FOREIGN KEY (`task_priority_id`) REFERENCES `task_priorities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tasks_task_category_id_foreign` FOREIGN KEY (`task_category_id`) REFERENCES `task_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tasks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `task_attachments`
--
ALTER TABLE `task_attachments`
  ADD CONSTRAINT `task_attachments_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `task_attachments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `task_comments`
--
ALTER TABLE `task_comments`
  ADD CONSTRAINT `task_comments_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `task_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `task_notes`
--
ALTER TABLE `task_notes`
  ADD CONSTRAINT `task_notes_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `task_notes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `task_signoff_requests`
--
ALTER TABLE `task_signoff_requests`
  ADD CONSTRAINT `task_signoff_requests_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `task_signoff_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `task_user`
--
ALTER TABLE `task_user`
  ADD CONSTRAINT `task_user_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `task_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ticket_category_id_foreign` FOREIGN KEY (`ticket_category_id`) REFERENCES `ticket_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tickets_ticket_priority_id_foreign` FOREIGN KEY (`ticket_priority_id`) REFERENCES `ticket_priorities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tickets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD CONSTRAINT `ticket_replies_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ticket_replies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `todos`
--
ALTER TABLE `todos`
  ADD CONSTRAINT `todos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `uploads`
--
ALTER TABLE `uploads`
  ADD CONSTRAINT `uploads_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `uploads_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_bank_accounts`
--
ALTER TABLE `user_bank_accounts`
  ADD CONSTRAINT `user_bank_accounts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_contacts`
--
ALTER TABLE `user_contacts`
  ADD CONSTRAINT `user_contacts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_contracts`
--
ALTER TABLE `user_contracts`
  ADD CONSTRAINT `user_contracts_contract_type_id_foreign` FOREIGN KEY (`contract_type_id`) REFERENCES `contract_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_contracts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_designations`
--
ALTER TABLE `user_designations`
  ADD CONSTRAINT `user_designations_designation_id_foreign` FOREIGN KEY (`designation_id`) REFERENCES `designations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_designations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_documents`
--
ALTER TABLE `user_documents`
  ADD CONSTRAINT `user_documents_document_type_id_foreign` FOREIGN KEY (`document_type_id`) REFERENCES `document_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_documents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_employments`
--
ALTER TABLE `user_employments`
  ADD CONSTRAINT `user_employments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_experiences`
--
ALTER TABLE `user_experiences`
  ADD CONSTRAINT `user_experiences_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_leaves`
--
ALTER TABLE `user_leaves`
  ADD CONSTRAINT `user_leaves_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_leave_details`
--
ALTER TABLE `user_leave_details`
  ADD CONSTRAINT `user_leave_details_leave_type_id_foreign` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_leave_details_user_leave_id_foreign` FOREIGN KEY (`user_leave_id`) REFERENCES `user_leaves` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_locations`
--
ALTER TABLE `user_locations`
  ADD CONSTRAINT `user_locations_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_locations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_qualifications`
--
ALTER TABLE `user_qualifications`
  ADD CONSTRAINT `user_qualifications_education_level_id_foreign` FOREIGN KEY (`education_level_id`) REFERENCES `education_levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_qualifications_qualification_language_id_foreign` FOREIGN KEY (`qualification_language_id`) REFERENCES `qualification_languages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_qualifications_qualification_skill_id_foreign` FOREIGN KEY (`qualification_skill_id`) REFERENCES `qualification_skills` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_qualifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_salaries`
--
ALTER TABLE `user_salaries`
  ADD CONSTRAINT `user_salaries_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_salaries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_salary_details`
--
ALTER TABLE `user_salary_details`
  ADD CONSTRAINT `user_salary_details_salary_head_id_foreign` FOREIGN KEY (`salary_head_id`) REFERENCES `salary_heads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_salary_details_user_salary_id_foreign` FOREIGN KEY (`user_salary_id`) REFERENCES `user_salaries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_shifts`
--
ALTER TABLE `user_shifts`
  ADD CONSTRAINT `user_shifts_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_shifts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
