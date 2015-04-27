<?php
	/***************************************************************************\
	* Open-Realty																*
	* http://www.open-realty.org												*
	* Written by Ryan C. Bonham <ryan@transparent-tech.com>						*
	* Copyright 2002, 2003 Transparent Technologies								*
	* --------------------------------------------								*
	* This file is part of Open-Realty.											*
	*																			*
	* Open-Realty is free software; you can redistribute it and/or modify		*
	* it under the terms of the Open-Realty License as published by				*
	* Transparent Technologies; either version 1 of the License, or				*
	* (at your option) any later version.										*
	*																			*
	* Open-Realty is distributed in the hope that it will be useful,			*
	* but WITHOUT ANY WARRANTY; without even the implied warranty of			*
	* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the				*
	* Open-Realty License for more details.										*
	*																			*
	* You should have received a copy of the Open-Realty License				*
	* along with Open-Realty; if not, write to Transparent Technologies			*
	* RR1 Box 162C, Kingsley, PA  18826  USA									*
	\***************************************************************************/

// TURKISH LANGUAGE FILE - Translated by vakuum (wkolbi@web.de) - Jul/08 Updated by Murat (paralaks@hotmail.com)

//Step 1: Check File Permissions:
$this->lang['install_Page_Title'] = 'Open-Realty Kurulumu';
$this->lang['install_version_warn'] = 'PHP versiyonunuz bu Open-Realty versiyonunu çalıştıramamaktadır. Kuruluma son verildi.';
$this->lang['install_sqlversion_warn'] = 'MySQL versiyonunuz bu Open-Realty versiyonunu çalıştıramamaktadır. Kuruluma son verildi.';
$this->lang['install_php_version'] = 'Mevcut PHP versiyonunuz ';
$this->lang['install_sql_version'] = 'Mevcut MySql versiyonunuz ';
$this->lang['install_php_required'] = 'Bu Open-Realty versiyonunun ihtiyaç duyduğu minimum PHP versiyonu : ';
$this->lang['install_sql_required'] = 'Bu Open-Realty versiyonunun ihtiyaç duyduğu minimum MySQL versiyonu : ';
$this->lang['install_welcome'] = 'Open-Realty kurulum aracına hoş geldiniz.';
$this->lang['install_intro'] = 'Bu program Open-Realty kurulumu sırasında size rehberlik edecektir. Kuruluma başlamadan önce sisteminizde boş bir veritabanı oluşturmuş olmanız gerekmektedir. Bununla beraber sunucunuzun ayarlarına bağlı olarak Linux sistemlerde (include/common.php, images/listing_photo, images/user_photos) klasörlerinin dosya erişim izinlerinin  777 veya 755 olarak ayarlanmış olması gerekmektedir. Open-Realty kurulumu bittikten sonra güvenlik sebebiyle common.php dosyası\'nın erişim izni 644 olarak değiştirilmelidir. Lütfen tüm ayarlar için <a href="http://wiki.open-realty.org/index.php/Install_guide">kurulum rehberi</a>ni inceleyin.';
$this->lang['install_step_one_header'] = "Adım 1: Dosya İzinlerini Kontrol Edin:";
$this->lang['install_Permission_on'] = 'Erişim var';
$this->lang['install_are_correct'] = ' doğru.';
$this->lang['install_are_incorrect'] = ' yanlışlık var.';
$this->lang['install_all_correct'] = 'Tüm izinler doğru.';
$this->lang['install_continue'] = 'Yüklemeye devam etmek için klikleyin';
$this->lang['install_please_fix'] = 'Lütfen dosya erişim izinlerini düzeltin ve bu sayfayı tekrar yükleyin.';

//Step 1: Determine Install Type
$this->lang['install_select_type'] = 'Yükleme Tipini Seçin';
$this->lang['upgrade_115'] = 'Open-Realty 1.1.5\'den yükseltme';
$this->lang['install_200'] = 'Open-Realty 2.x.x yeni kurulum';
$this->lang['move'] = 'Sadece Yol ve URL bilgisini güncelle';
$this->lang['upgrade_200'] = 'Open-Realty 2.x.x\'ten yükseltme (2.0.0 Beta 1 veya daha büyük bir versiyon)';

//Step 2: Setup Database Connection:
$this->lang['install_setup__database_settings'] = 'Veritabanı Bağlantısı:';
$this->lang['install_Database_Type'] = 'Veritabanı tipi:';
$this->lang['install_mySQL'] = 'mySQL';
$this->lang['install_PostgreSQL'] = 'PostgreSQL';
$this->lang['install_Database_Server'] = 'Veritabanı Sunucusu:';
$this->lang['install_Database_Name'] = 'Veritabanı ismi:';
$this->lang['install_Database_User'] = 'Veritabanı kullanıcı ismi:';
$this->lang['install_Database_Password'] = 'Veritabanı kullanıcı şifresi:';
$this->lang['install_Table Prefix'] = 'Tablo ön-eki:';
$this->lang['install_Base_URL'] = 'Başlangıç URL\'i:';
$this->lang['install_Base_Path'] = 'Dosya Sistemi Başlangıç Yolu :';
$this->lang['install_Language'] = 'Dil:';
$this->lang['install_English'] = 'İnglizce';
$this->lang['install_Spanish'] = 'İspanyolca';
$this->lang['install_Italian'] = 'İtalyanca';
$this->lang['install_French'] = 'Fransızca';
$this->lang['install_Portuguese'] = "Portekizce";
$this->lang['install_Russian'] = "Rusça";
$this->lang['install_Turkish'] = "Türkçe";
$this->lang['install_German'] = "Almanca";
$this->lang['install_Dutch'] = "Hollandaca";
$this->lang['install_Lithuanian'] = "Lithuanian";
$this->lang['install_Arabic'] = "Arabic";
$this->lang['install_Polish'] = "Polish";
$this->lang['install_Czech'] = "Czech";
$this->lang['install_connection_fail'] = 'Veritabanına bağlantısı sağlanamadı. Lütfen ayarlarınızı kontrol edin ve tekrar deneyin.';

//Step Three
$this->lang['install_get_old_version'] = 'Eski Open-Realty versiyonu belirleniyor';
$this->lang['install_get_old_version_error'] = 'Eski Open-Realty versiyonunu belirlemede hata oluştu. Yükseltme işlemine devam edilemiyor.';
$this->lang['install_cleared_cache'] = 'Geçici Bellek Silindi';
$this->lang['install_connection_ok'] = 'Veriı bağlantısı sağndı.';
$this->lang['install_save_settings'] = 'Ayarlarınız common.php dosyasına kaydedilecek.';
$this->lang['install_settings_saved'] = 'Veritabanı ayarlar kaydedildi.';
$this->lang['install_continue_db_setup'] = 'Veritabanı kurulumuna devam edin.';
$this->lang['install_populate_db'] = 'Şimdi veritabanı içerisine gerekli bilgiler yüklenecek.';

//finalize installation
$this->lang['install_installation_complete'] = 'Kurulum tamamlandı.';
$this->lang['install_configure_installation'] = 'Kurulumunuzun ayarlarını yapmak için buraya tıklayın';

//2.2.0 additions
$this->lang['install_devel_mode'] = 'Geliştirici Modu Kurulumu - Hata olsa bile kurulumun devam etmesine izin verecektir. TAVSİYE EDİLMEMEKTEDR.';
$this->lang['yes'] = 'EVET';
$this->lang['no'] = 'HAYIR';

?>