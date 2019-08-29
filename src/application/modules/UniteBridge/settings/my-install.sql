INSERT IGNORE INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES  ('unite-bridge', 'Unite Bridge', '', '1.0.0', 1, 'extra') ;

INSERT IGNORE INTO `engine4_core_settings` (`name` , `value`) VALUES ('unite.url', '');
INSERT IGNORE INTO `engine4_core_settings` (`name` , `value`) VALUES ('unite.auth', '');
INSERT IGNORE INTO `engine4_core_settings` (`name` , `value`) VALUES ('unite.token', '');
INSERT IGNORE INTO `engine4_core_settings` (`name` , `value`) VALUES ('unite.apiKey', '');
INSERT IGNORE INTO `engine4_core_settings` (`name` , `value`) VALUES ('unite.siteId', '');
INSERT IGNORE INTO `engine4_core_settings` (`name` , `value`) VALUES ('unite.versionId', '');
INSERT IGNORE INTO `engine4_core_settings` (`name` , `value`) VALUES ('unite.viewerToken', '');

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('core_admin_main_plugins_unite', 'unite-bridge', 'Unite Bridge', '', '{"route":"admin_default","module":"unite-bridge","controller":"manage"}', 'core_admin_main_plugins', '', 999);

--
-- SHA1: f95366b38af2cc1df79f282010e617c3f1c2f9e2
--

INSERT IGNORE INTO `engine4_core_settings` (`name` , `value`) VALUES ('unite.componentHeader', '@SE/SEPHPBridge/Layout/Header.js');
INSERT IGNORE INTO `engine4_core_settings` (`name` , `value`) VALUES ('unite.componentFooter', '@SE/SEPHPBridge/Layout/Footer.js');
