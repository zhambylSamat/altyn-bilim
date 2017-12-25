

CREATE TABLE `admin` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `admin_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `surname` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `password2` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `max_access` int(1) NOT NULL DEFAULT '0',
  `role` varchar(9) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'moderator',
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_num` (`admin_num`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `answer` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `answer_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `question_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `answer_text` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `answer_img` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `torf` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `answer_num` (`answer_num`)
) ENGINE=MyISAM AUTO_INCREMENT=1121 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `attendance_notification` (
  `attendance_notification_num` int(3) NOT NULL AUTO_INCREMENT,
  `group_student_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `first_abs` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `second_abs` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `action` float NOT NULL,
  PRIMARY KEY (`attendance_notification_num`),
  UNIQUE KEY `group_student_num` (`group_student_num`)
) ENGINE=MyISAM AUTO_INCREMENT=231 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `child` (
  `child_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `parent_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `student_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`child_num`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `device_mac_address` (
  `mac_address` varchar(17) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`mac_address`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `group_info` (
  `group_info_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `subject_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `teacher_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `group_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N/A',
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `start_lesson` time NOT NULL DEFAULT '00:00:00',
  `finish_lesson` time NOT NULL DEFAULT '00:00:00',
  `office_number` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `group_student` (
  `group_student_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `group_info_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `student_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`group_student_num`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `news` (
  `type` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `header` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `img` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `readed` int(1) NOT NULL DEFAULT '0',
  `last_updated_date` date NOT NULL,
  `publish` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;






CREATE TABLE `parent` (
  `parent_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `surname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent_num`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `problem_solution` (
  `problem_solution_id` int(10) NOT NULL AUTO_INCREMENT,
  `document_link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `subtopic_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `inserted_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`problem_solution_id`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `progress_group` (
  `progress_group_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `group_info_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `created_date` date NOT NULL,
  PRIMARY KEY (`progress_group_num`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `progress_student` (
  `progress_student_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `progress_group_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `student_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `attendance` int(1) NOT NULL,
  `home_work` float NOT NULL,
  PRIMARY KEY (`progress_student_num`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `question` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `question_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `test_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `question_text` text COLLATE utf8_unicode_ci NOT NULL,
  `question_img` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `question_num` (`question_num`)
) ENGINE=MyISAM AUTO_INCREMENT=145 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `quiz` (
  `quiz_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `topic_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`quiz_num`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `quiz_mark` (
  `quiz_mark_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `quiz_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `student_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `mark_theory` int(3) NOT NULL,
  `mark_practice` int(3) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`quiz_mark_num`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `rename_folder` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `folder_name` varchar(30) NOT NULL,
  `tmp_name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;






CREATE TABLE `review` (
  `review_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `review_info_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `group_student_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `status` text COLLATE utf8_unicode_ci,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`review_num`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `review_info` (
  `review_info_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `review_text` text COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'review',
  PRIMARY KEY (`review_info_num`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `schedule` (
  `schedule_id` int(5) NOT NULL AUTO_INCREMENT,
  `group_info_num` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `week_id` int(1) NOT NULL,
  PRIMARY KEY (`schedule_id`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8;






CREATE TABLE `student` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `student_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `surname` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `gender` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `img_profile` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `password_type` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `block` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_num` (`student_num`)
) ENGINE=MyISAM AUTO_INCREMENT=162 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `student_permission` (
  `student_permission_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `student_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`student_permission_num`),
  UNIQUE KEY `student_test_permission` (`student_permission_num`),
  UNIQUE KEY `student_num` (`student_num`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `student_prize_notification` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `group_student_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `quiz_mark_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `quiz_mark_num` (`quiz_mark_num`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `student_progress` (
  `student_progress_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `student_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `subtopic_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `progress` float NOT NULL,
  PRIMARY KEY (`student_progress_num`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `student_test_permission` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `student_permission_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `subtopic_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `video_permission` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `test_permission` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `done` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'n',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1953 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `subject` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `subject_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `subject_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subject_num` (`subject_num`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `subtopic` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `subtopic_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `topic_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `subtopic_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `subtopic_order` int(2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subtopic_num` (`subtopic_num`)
) ENGINE=MyISAM AUTO_INCREMENT=261 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `suggestion` (
  `suggestion_id` int(6) NOT NULL AUTO_INCREMENT,
  `teacher_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `status` int(1) NOT NULL,
  `last_changed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`suggestion_id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `teacher` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `teacher_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `surname` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `gender` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `img_profile` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `password_type` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default',
  PRIMARY KEY (`id`),
  UNIQUE KEY `teacher_num` (`teacher_num`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `test` (
  `test_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `topic_num` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subtopic_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `test_comment` text COLLATE utf8_unicode_ci,
  `last_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`test_num`),
  UNIQUE KEY `test_num` (`test_num`),
  UNIQUE KEY `subtopic_num` (`subtopic_num`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `test_result` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `test_result_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `student_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `test_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `submit_date` date NOT NULL,
  `result` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `test_result_num` (`test_result_num`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `tmp_student_test_permission` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `student_permission_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `subtopic_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `video_permission` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `test_permission` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `done` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'n',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `topic` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `topic_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `subject_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `topic_name` text COLLATE utf8_unicode_ci NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `topic_order` int(2) NOT NULL,
  `quiz` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'n',
  PRIMARY KEY (`id`),
  UNIQUE KEY `topic_num` (`topic_num`)
) ENGINE=MyISAM AUTO_INCREMENT=114 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `transfer` (
  `transfer_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `new_group_info_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `old_group_info_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `student_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`transfer_num`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `trial_test` (
  `trial_test_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `subject_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `student_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`trial_test_num`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `trial_test_mark` (
  `trial_test_mark_num` int(5) NOT NULL AUTO_INCREMENT,
  `trial_test_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `mark` int(2) NOT NULL,
  `date_of_test` date NOT NULL,
  PRIMARY KEY (`trial_test_mark_num`)
) ENGINE=MyISAM AUTO_INCREMENT=448 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `user_connection_tmp` (
  `student_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`student_num`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `video` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `video_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `subtopic_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `video_link` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `video_num` (`video_num`)
) ENGINE=MyISAM AUTO_INCREMENT=181 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `video_comment` (
  `video_comment_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `subtopic_num` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`video_comment_num`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;




