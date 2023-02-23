# macauccc-donate
macauccc-donate 基督教宣道堂在線捐款


INSERT INTO `clickrcms_setting` (`setting_group`, `setting_key`, `setting_value`, `description`, `status`, `updated_at`)
VALUES
  ('custom', 'donate_church1', '堂會一,堂會二,後台基本設定>自定義設定裡改', '繁體版堂會', 1, '2023-02-23 14:52:04');


ALTER TABLE `clickrcms_order` ADD `donate_church` VARCHAR(100) NULL COMMENT '堂會' AFTER `donate_money`; 