CREATE DATABASE IF NOT EXISTS `homewizard` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `homewizard`;

CREATE TABLE IF NOT EXISTS `cronhistory` (
  `cron` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(10) NOT NULL,
  `actie` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `cronhistory`
 ADD PRIMARY KEY (`cron`,`timestamp`);
 
 CREATE TABLE IF NOT EXISTS `energylink` (
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `netto` float NOT NULL,
  `S1` float NOT NULL,
  `S2` float NOT NULL,
  `gas` float NOT NULL,
  `verbruik` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `energylink`
 ADD PRIMARY KEY (`timestamp`);
 
CREATE TABLE IF NOT EXISTS `history` (
  `id_sensor` smallint(6) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `history`
 ADD PRIMARY KEY (`id_sensor`,`time`);

CREATE TABLE IF NOT EXISTS `rain` (
  `date` char(10) NOT NULL,
  `mm` float NOT NULL,
  `id_sensor` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `rain`
 ADD PRIMARY KEY (`date`,`id_sensor`);

CREATE TABLE IF NOT EXISTS `sensors` (
  `id_sensor` smallint(6) NOT NULL,
  `volgorde` smallint(6) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `favorite` varchar(3) NOT NULL,
  `tempk` float NOT NULL DEFAULT '0',
  `tempw` float NOT NULL DEFAULT '22',
  `correctie` float NOT NULL DEFAULT '0',
  `ttt` float NOT NULL DEFAULT '15'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `sensors`
 ADD PRIMARY KEY (`id_sensor`,`type`);

CREATE TABLE IF NOT EXISTS `settings` (
  `variable` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(10000) COLLATE utf8_unicode_ci NOT NULL,
  `favorite` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `settings`
 ADD PRIMARY KEY (`variable`,`user`), ADD KEY `user` (`user`);

CREATE TABLE IF NOT EXISTS `statusses` (
  `status` varchar(200) NOT NULL,
  `omschrijving` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `statusses`
 ADD PRIMARY KEY (`status`);
 
CREATE TABLE IF NOT EXISTS `switches` (
  `id_switch` smallint(6) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `favorite` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `volgorde` smallint(6) DEFAULT NULL,
  `temp` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `switches`
 ADD PRIMARY KEY (`id_switch`,`type`);
 
CREATE TABLE IF NOT EXISTS `switchhistory` (
  `id_switch` smallint(6) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `who` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'm'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `temperature` (
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `te` float NOT NULL,
  `hu` tinyint(4) NOT NULL,
  `id_sensor` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `temperature`
 ADD PRIMARY KEY (`timestamp`,`id_sensor`);

CREATE TABLE IF NOT EXISTS `temp_day` (
  `date` char(10) NOT NULL,
  `min` float NOT NULL,
  `max` float NOT NULL,
  `id_sensor` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `temp_day`
 ADD PRIMARY KEY (`date`,`id_sensor`);

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` char(64) COLLATE utf8_unicode_ci NOT NULL,
  `salt` char(16) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `users`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`);

ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1; 
 
CREATE TABLE IF NOT EXISTS `versie` (
`id` int(11) NOT NULL,
  `versie` int(11) NOT NULL,
  `datumupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `versie`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `versie`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1; 
 
CREATE TABLE IF NOT EXISTS `wind` (
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `wi` float NOT NULL,
  `gu` float NOT NULL,
  `dir` smallint(6) NOT NULL,
  `id_sensor` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `wind`
 ADD PRIMARY KEY (`timestamp`,`id_sensor`); 
 
CREATE TABLE IF NOT EXISTS `wind_day` (
  `date` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `id_sensor` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `wind_day`
 ADD PRIMARY KEY (`date`,`id_sensor`); 
 
 
 
 




INSERT IGNORE INTO `statusses` (`status`, `omschrijving`) VALUES
('contactno', 'Gesloten'),
('contactyes', 'Open'),
('doorbellyes', 'Gebeld'),
('lightno', 'Licht'),
('lightyes', 'Donker'),
('motionno', 'motionno'),
('motionyes', 'Beweging'),
('smokeno', 'Tested'),
('smokeyes', 'ROOK!!!');

INSERT IGNORE INTO `settings` (`variable`, `value`) VALUES
('acceptedip', '127.0.0.1'),
('acceptedip2', '1.2.3.4'),
('debug', 'no'),
('defaultthermometer', '1'),
('detailscenes', 'optional'),
('developerjson', '{"status": "ok", "version": "2.84", "request": {"route": "/get-sensors" }, "response": {"switches" : [{"id":0,"name":"Pluto","type":"switch","status":"off","favorite":"yes"},{"id":1,"name":"Licht Garage","type":"switch","status":"off","favorite":"no"},{"id":2,"name":"Bureel Tobi","type":"switch","status":"off","favorite":"no"},{"id":3,"name":"Lamp Bureel","type":"switch","status":"on","favorite":"no"},{"id":4,"name":"TV","type":"switch","status":"off","favorite":"yes"},{"id":5,"name":"Radio","type":"switch","status":"on","favorite":"yes"},{"id":6,"name":"Badkamer","type":"radiator","tte":10.0,"favorite":"yes"},{"id":7,"name":"Slaapkamer","type":"radiator","tte":8.0,"favorite":"no"},{"id":8,"name":"Slaapkamer Tobi","type":"radiator","tte":8.0,"favorite":"no"},{"id":9,"name":"Diskstation","type":"virtual","status":"off","favorite":"no"},{"id":10,"name":"Eettafel","type":"dimmer","status":"off","dimlevel":0,"favorite":"no"},{"id":11,"name":"Zithoek","type":"dimmer","status":"off","dimlevel":0,"favorite":"no"},{"id":12,"name":"Brander","type":"switch","status":"off","favorite":"no"},{"id":13,"name":"Zonneluifel","type":"somfy","favorite":"no"},{"id":14,"name":"Eetplaats","type":"radiator","tte":15.0,"favorite":"no"},{"id":15,"name":"Zitplaats","type":"radiator","tte":8.0,"favorite":"no"}],"uvmeters":[],"windmeters":[{"id":2,"name":"Windmeter","code":"10321553","model":1,"lowBattery":"yes","version":2.19,"unit":0,"ws":0.1,"dir":"NNW 337","gu":0.0,"wc":5.5,"te":5.5,"ws+":3.2,"ws+t":"14:35","ws-":0.1,"ws-t":"18:26","favorite":"no"}],"rainmeters":[{"id":3,"name":"Regenmeter","code":"4091779","model":1,"lowBattery":"no","version":2.19,"mm":0.0,"3h":0.0,"favorite":"no"}],"thermometers":[{"id":1,"name":"Buiten","code":"13666960","model":1,"lowBattery":"no","version":2.19,"te":6.4,"hu":83,"te+":8.0,"te+t":"14:48","te-":2.2,"te-t":"06:44","hu+":89,"hu+t":"05:39","hu-":69,"hu-t":"14:32","outside":"yes","favorite":"no"},{"id":4,"name":"Badkamer","channel":1,"model":0,"outside":"no","favorite":"no"}],"weatherdisplays":[{"id":0,"name":"Weerstation","code":"11657828","model":1,"version":2.20,"favorite":"no"}], "energymeters": [], "energylinks": [], "heatlinks": [], "hues": [], "scenes": [{"id": 0, "name": "Alles", "favorite": "yes"}], "kakusensors": [{"id":0,"name":"Zolder","status":null,"type":"smoke","favorite":"no","timestamp":"00:00","cameraid":null},{"id":1,"name":"Poort","status":"no","type":"contact","favorite":"no","timestamp":"18:00","cameraid":null},{"id":2,"name":"Garage","status":"no","type":"motion","favorite":"no","timestamp":"18:13","cameraid":null},{"id":3,"name":"Hal boven","status":null,"type":"smoke","favorite":"no","timestamp":"00:00","cameraid":null},{"id":4,"name":"Deurbel","status":null,"type":"doorbell","favorite":"no","timestamp":"00:00","cameraid":null}], "cameras": []}}\r\n\r\n\r\n'),
('developermode', 'no'),
('jsonurl', 'http://adres:port/password/'),
('positie_energylink', '7'),
('positie_radiatoren', '3'),
('positie_regen', '8'),
('positie_scenes', '2'),
('positie_schakelaars', '1'),
('positie_sensoren', '4'),
('positie_somfy', '3'),
('positie_temperatuur', '6'),
('positie_wind', '9'),
('refreshinterval', '30'),
('positie_energylink', '7'),
('defaultthermometer', '1'),
('toon_radiatoren', 'yes'),
('toon_regen', 'yes'),
('toon_scenes', 'yes'),
('toon_schakelaars', 'yes'),
('toon_sensoren', 'yes'),
('toon_somfy', 'yes'),
('toon_temperatuur', 'yes'),
('toon_wind', 'yes'),
('toon_energylink', 'yes'),
('css_td_newgroup', 'border-top:1px solid black; padding-top:10px;'),
('css_body', ''),
('css_item', ''),
('email_from', 'guy@egregius.be'),
('email_notificatie', 'guy@egregius.be'),
('toon_acties', 'yes'),
('positie_acties', '9'),
('css_h1', 'font-weight:100;font-size:40px;'),
('css_h2', 'font-weight:200;font-size:22px;'),
('css_h3', 'font-weight:200;font-size:18px;'),
('toon_schakelaars2', 'yes')
;

INSERT IGNORE INTO users (id, username, password, salt) VALUES 
(NULL, 'default', '1be8bc8019a469136fc1c1f4761ee82d09b8faabc3bdb6f5b48876bf6f8c2613', '3a2043a64fba5959');

INSERT IGNORE INTO `versie` (`versie`) VALUES
(20150219);
