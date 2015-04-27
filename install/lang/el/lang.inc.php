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

// GREEK LANGUAGE FILE - Translated by Georgopoulos Nikos (geonick@freemail.gr) at Jul/2008

//Step 1: Check File Permissions:
$this->lang['install_Page_Title'] = "Εγκατάσταση Open-Realty";
$this->lang['install_version_warn'] = "Η έκδοση της php σας δε μπορεί να εκτελέσει την παρούσα έκδοση του Open-Realty. Η εγκατάσταση ματαιώθηκε.";
$this->lang['install_sqlversion_warn'] = "Η έκδοση της mysql σας δε μπορεί να εκτελέσει την παρούσα έκδοση του Open-Realty. Η εγκατάσταση ματαιώθηκε.";
$this->lang['install_php_version'] = "Η τρέχουσα έκδοση της PHP σας είναι ";
$this->lang['install_sql_version'] = "Η τρέχουσα έκδοση της MySql σας είναι ";
$this->lang['install_php_required'] = "Η τρέχουσα έκδοση του Open-Realty απαιτεί τουλάχιστο την έκδοση PHP ";
$this->lang['install_sql_required'] = "Η τρέχουσα έκδοση του Open-Realty απαιτεί τουλάχιστο την έκδοση MySql ";
$this->lang['install_welcome'] = "Καλωσήλθατε στο εργαλείο εγκατάστασης του Open-Realty.";
$this->lang['install_intro'] = "Αυτό το εργαλείο θα σας βοηθήσει να ρυθμίσετε την εγκατάσταση του open-realty. Πριν ξεκινήσετε θα πρέπει να δημιουργήσετε μία κενή βάση δεδομένων στο σύστημά σας. Θα πρέπει επίσης να έχετε τα δικαιώματα των παρακάτω αρχείων/φακέλων ρυθμισμένα σε 777 ή 755 ανάλογα με τις ρυθμίσεις του εξυπηρετητή σας. (include/common.php, images/listing_photo, images/user_photos)  Όταν τελειώσετε με την εγκατάσταση του open-realty θα πρέπει να επαναφέρετε το common.php σε δικαιώματα 644, για λόγους ασφαλείας. Παρακαλώ διαβάστε το <a href=\"http://wiki.open-realty.org/index.php/Install_guide\">βοηθό εγκατάστασης</a> για περισσότερες πληροφορίες.";
$this->lang['install_step_one_header'] = "Βήμα 1: Έλεγχος δικαιωμάτων αρχείων:";
$this->lang['install_Permission_on'] = "Τα δικαιώματα στο";
$this->lang['install_are_correct'] = "είναι σωστά";
$this->lang['install_are_incorrect'] = "δεν είναι σωστά";
$this->lang['install_all_correct'] = "Όλα τα δικαιώματα αρχείων είναι σωστά.";
$this->lang['install_continue'] = "Κάντε κλικ για να συνεχίσετε την εγκατάσταση";
$this->lang['install_please_fix'] = "Παρακαλώ διορθώστε τα δικαιώματα αρχείων και στη συνέχεια ανανεώστε την παρούσα σελίδα.";

//Step 1: Determine Install Type
$this->lang['install_select_type'] = 'Επιλέξτε τύπο εγκατάστασης:';
$this->lang['upgrade_115'] = 'Ενημέρωση από το Open-Realty 1.1.5';
$this->lang['install_200'] = 'Νέα εγκατάσταση του Open-Realty 2.x.x';
$this->lang['move'] = 'Ενημέρωση του μονοπατιού (Path) και της διαδρομής (URL) μόνο';
$this->lang['upgrade_200'] = 'Ενημέρωση από το Open-Realty 2.x.x (2.0.0 Beta 1 ή μεγαλύτερο)';

//Step 2: Setup Database Connection:
$this->lang['install_setup__database_settings'] = "Ρύθμιση σύνδεσης με τη βάση δεδομένων:";
$this->lang['install_Database_Type'] = "Τύπος βάσης δεδομένων:";
$this->lang['install_mySQL'] = "mySQL";
$this->lang['install_PostgreSQL'] = "PostgreSQL";
$this->lang['install_Database_Server'] = "Εξυπηρετητής βάσης δεδομένων:";
$this->lang['install_Database_Name'] = "Όνομα βάσης δεδομένων:";
$this->lang['install_Database_User'] = "Χρήστης βάσης δεδομένων:";
$this->lang['install_Database_Password'] = "Κωδικός πρόσβασης βάσης δεδομένων:";
$this->lang['install_Table Prefix'] = "Πρόθεμα πινάκων:";
$this->lang['install_Base_URL'] = "Διεύθυνση (URL) βάσης:";
$this->lang['install_Base_Path'] = "Μονοπάτι (Path) βάσης:";
$this->lang['install_Language'] = "Γλώσσα:";
$this->lang['install_English'] = "Αγγλικά";
$this->lang['install_Spanish'] = "Ισπανικά";
$this->lang['install_Italian'] = "Ιταλικά";
$this->lang['install_French'] = "Γαλλικά";
$this->lang['install_Portuguese'] = "Πορτογαλλικά";
$this->lang['install_Russian'] = "Ρώσικα";
$this->lang['install_Turkish'] = "Τούρκικα";
$this->lang['install_German'] = "Γερμανικά";
$this->lang['install_Dutch'] = "Ολλανδικά";
$this->lang['install_Lithuanian'] = "Lithuanian";
$this->lang['install_Arabic'] = "Arabic";
$this->lang['install_Polish'] = "Polish";
$this->lang['install_Czech'] = "Czech";
$this->lang['install_connection_fail'] = "Δεν ήταν δυνατή η σύνδεση με τη βάση δεδομένων. Παρακαλώ ελέγξτε τις ρυθμίσεις σας και ξαναπροσπαθήστε.";

//Step Three
$this->lang['install_get_old_version'] = 'Εντοπισμός της παλιάς έκδοσης του Open-Realty';
$this->lang['install_get_old_version_error'] = 'Λάθος στη διαδικασία εντοπισμού της παλιάς έκδοσης του Open-Realty. Η ενημέρωση δεν είναι δυνατό να συνεχιστεί.';
$this->lang['install_cleared_cache'] = "Η προσωρινή μνήμη καθαρίστηκε";
$this->lang['install_connection_ok'] = "Η σύνδεση με τη βάση δεδομένων ήταν επιτυχής.";
$this->lang['install_save_settings'] = "Οι ρυθμίσεις σας θα αποθηκευθούν στο αρχείο common.php";
$this->lang['install_settings_saved'] = "Οι ρυθμίσεις της βάσης δεδομένων αποθηκεύθηκαν.";
$this->lang['install_continue_db_setup'] = "Συνέχεια για ρύθμιση της βάσης δεδομένων.";
$this->lang['install_populate_db'] = "Συνέχεια για δημοσίευση της βάσης δεδομένων.";

//finalize installation
$this->lang['install_installation_complete'] = "Η εγκατάσταση ολοκληρώθηκε.";
$this->lang['install_configure_installation'] = "Κάντε κλικ εδώ για να παραμετροποιήσετε την εγκατάσταση";

//2.2.0 additions.
$this->lang['install_devel_mode'] = "Εγκατάσταση σε κατάσταση Developer - Αυτό θα επιτρέψει στην εγκατάσταση να συνεχίσει ακόμη και με σφάλματα. ΑΥΤΟ ΔΕ ΣΥΝΙΣΤΑΤΑΙ.";
$this->lang['yes'] = "Ναι";
$this->lang['no'] = "Όχι";

?>