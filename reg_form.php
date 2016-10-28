<?php
# @name: reg_form.php
# @version: 0.6
# @license: GNU General Public License version 3 (GPLv3) <https://www.gnu.org/licenses/gpl-3.0.en.html>
# @purpose: Submit user data to Imperial College Library's Ex Libris Alma library management system
# @author: Karine Larose <k.larose@imperial.ac.uk>
# @author: Simon Barron <s.barron@imperial.ac.uk>
# @acknowledgements: 
# jQuery for date-picker: Copyright 2015 jQuery Foundation and other contributors; Licensed MIT http://webdesignandsuch.com/add-a-calendar-date-picker-to-a-form-with-jquery/
# jQuery for dynamic HTML forms: https://stackoverflow.com/questions/13426472/dynamic-html-form
# Most of the PHP form handling: http://www.w3schools.com/php/php_form_validation.asp
# Doing a POST request without using cURL: https://stackoverflow.com/questions/1660983/sending-xml-data-using-http-post-with-php
# Sending email on form submit: https://stackoverflow.com/questions/18379238/send-email-with-php-from-html-form-on-submit-with-the-same-script
?>

<?php
// PHP to define variables and fix the input from the form before sending it to XML
    $almaurl="https://api-eu.hosted.exlibrisgroup.com";
    $apikey="xxxxxxxxxxxxxx";
    
// clean up all inputted data
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

// define variables and set to empty values
$first_name = $last_name = $user_group = $campus_code = $birth_date = $expiry_date = $line1 = $line2 = $city = $postcode = $start_date = $email_address = $phone_number = $ucat1 = $ucat2 = $ucat3 = $note1 = "";

    $primary_id = uniqid('IMP_');
    $start_date = date("Y-m-d");
    $eight_weeks_later = date("Y-m-d", strtotime("+8 weeks"));
    $user_group = $_POST["user_group"];

    if ($user_group == "SRXLOAN") {
        $first_name = test_input($_POST["first_name_sconul"]);
        $last_name = test_input($_POST["last_name_sconul"]);
        $password = xxxxxxxxxxxxxx;
        $campus_code = test_input($_POST["campus_code_sconul"]);
        $birth_date = date("Y-m-d", strtotime($_POST["birth_date_sconul"])). "Z";
        $expiry_date = date("Y-m-d", strtotime($_POST["expiry_date_sconul"])). "Z";
        if ($expiry_date > date("Y-m-d", strtotime("+3 years"))){
            $expiry_date = date("Y-m-d", strtotime("+3 years"));
        }
        $line1 = test_input($_POST["line1_sconul"]);
        $line2 = test_input($_POST["line2_sconul"]);
        $city = test_input($_POST["city_sconul"]);
        $postcode = test_input($_POST["postcode_sconul"]);
        $email_address = test_input($_POST["email_address_sconul"]);
        $phone_number = test_input($_POST["phone_number_sconul"]);
        $ucat1 = test_input($_POST["ucat1_sconul"]);
        if ($_POST["sconul_access"] == "SCONULVAC") {
            $user_group = "ICREF";
            $ucat2 = test_input($_POST["sconul_acess"]);
        } else {
            $ucat2 = test_input($_POST["ucat2_sconul"]);
        }
    }
    elseif($user_group == "NHS") {
        $first_name = test_input($_POST["first_name_NHS"]);
        $last_name = test_input($_POST["last_name_NHS"]);
        $password = xxxxxxxxxxxxxx;
        $campus_code = test_input($_POST["campus_code_NHS"]);
        $birth_date = date("Y-m-d", strtotime($_POST["birth_date_NHS"])). "Z";
        $expiry_date = date("Y-m-d", strtotime($_POST["expiry_date_NHS"])). "Z";
        if ($expiry_date > date("Y-m-d", strtotime("+1 year"))){
            $expiry_date = date("Y-m-d", strtotime("+1 year"));
        }
        $line1 = test_input($_POST["line1_NHS"]);
        $line2 = test_input($_POST["line2_NHS"]);
        $city = test_input($_POST["city_NHS"]);
        $postcode = test_input($_POST["postcode_NHS"]);
        $email_address = test_input($_POST["email_address_NHS"]);
        $department = test_input($_POST["department_NHS"]);
        $phone_number = test_input($_POST["phone_number_NHS"]);
        $ucat3 = test_input($_POST["ucat3_NHS"]);
        if ($_POST["nhs_status"] == "trust") {
            if ($_POST["mrc_NHS"] == "y") {
                $user_group = "STAFFIC";
                $ucat1 = "WC";
            }
            else {
                if ($_POST["ucat1_trust_NHS"] == "OTHER") {
                    $ucat1 = test_input($_POST["ucat1_trust_NHS"]);
                    $user_group = "MEDREF";
                    $note1 = "Library staff: please check eligibility to join.";
                }
                elseif($_POST["ucat1_trust_NHS"] == "HILLINGNHS" || $_POST["ucat1_trust_NHS"] == "RBHRB" || $_POST["ucat1_trust_NHS"] == "WESTMIDNHS") {
                    $ucat1 = test_input($_POST["ucat1_trust_NHS"]);
                    if ($campus_code == "CHELSEA") {
                        $user_group = "CWREF";
                    }
                    elseif($campus_code == "CHARINGX") {
                        $user_group = "CXREF";
                    }
                    elseif($campus_code == "HAMM") {
                        $user_group = "HHREF";
                    }
                    elseif($campus_code == "BROMPTON") {
                        $user_group = "RBREF";
                    }
                    elseif($campus_code == "ST_MARYS") {
                        $user_group = "SMREF";
                    }
                } 
                else {
                    $ucat1 = test_input($_POST["ucat1_trust_NHS"]);
                    if ($expiry_date > $eight_weeks_later) {
                        $user_group = "NHS";
                    } else {
                        if ($campus_code == "CHELSEA") {
                            $user_group = "CWREF";
                        }
                        elseif($campus_code == "CHARINGX") {
                            $user_group = "CXREF";
                        }
                        elseif($campus_code == "HAMM") {
                            $user_group = "HHREF";
                        }
                        elseif($campus_code == "BROMPTON") {
                            $user_group = "RBREF";
                        }
                        elseif($campus_code == "ST_MARYS") {
                            $user_group = "SMREF";
                        }
                    }
                }
            }
        }
        elseif($_POST["nhs_status"] == "placement") {
            if ($_POST["ucat1_placement_NHS"] == "OTHER") {
                $user_group = "MEDREF";
                $note1 = "Library staff: please check elibility to join.";
            } 
            else {
                $ucat1 = test_input($_POST["ucat1_placement_NHS"]);
                if ($expiry_date > $eight_weeks_later) {
                    if ($ucat3 == "STDMID" || $ucat3 == "STDNURS") {
                        $user_group = "NHSPS";
                    } else {
                        if ($campus_code == "CHELSEA") {
                            $user_group = "CWLOCAL";
                        }
                        elseif($campus_code == "CHARINGX") {
                            $user_group = "CXLOCAL";
                        }
                        elseif($campus_code == "HAMM") {
                            $user_group = "HHLOCAL";
                        }
                        elseif($campus_code == "BROMPTON") {
                            $user_group = "RBLOCAL";
                        }
                        elseif($campus_code == "ST_MARYS") {
                            $user_group = "SMLOCAL";
                        }
                    }
                } 
                else {
                    if ($campus_code == "CHELSEA") {
                        $user_group = "CWREF";
                    }
                    elseif($campus_code == "CHARINGX") {
                        $user_group = "CXREF";
                    }
                    elseif($campus_code == "HAMM") {
                        $user_group = "HHREF";
                    }
                    elseif($campus_code == "BROMPTON") {
                        $user_group = "RBREF";
                    }
                    elseif($campus_code == "ST_MARYS") {
                        $user_group = "SMREF";
                    }
                }
            }
        }
        if ($_POST["job_title_NHS"] == "Counsellor" || $_POST["job_title_NHS"] == "Dental Surgery Assistant" || $_POST["job_title_NHS"] == "Associate Practitioner" || $_POST["job_title_NHS"] == "Assistant Psychologist" || $_POST["job_title_NHS"] == "Assistant Psychotherapist" || $_POST["job_title_NHS"] == "Assistant Technician" || $_POST["job_title_NHS"] == "Healthcare" || $_POST["job_title_NHS"] == "Laboratory" || $_POST["job_title_NHS"] == "Medical Laboratory Assistant" || $_POST["job_title_NHS"] == "Nursery" || $_POST["job_title_NHS"] == "Operating Department Practitioner" || $_POST["job_title_NHS"] == "Phlebotomist" || $_POST["job_title_NHS"] == "Pre-reg Pharmacist" || $_POST["job_title_NHS"] == "Social Care" || $_POST["job_title_NHS"] == "Student Technician") {
            $ucat2 = "ADDCLINSER";
        }
        elseif ($_POST["job_title_NHS"] == "Biomedical Scientist" || $_POST["job_title_NHS"] == "Clinical Scientist" || $_POST["job_title_NHS"] == "Chaplain" || $_POST["job_title_NHS"] == "Clinical Psychologist" || $_POST["job_title_NHS"] == "Optometrist" || $_POST["job_title_NHS"] == "Osteopath" || $_POST["job_title_NHS"] == "Pharmacist" || $_POST["job_title_NHS"] == "Psychologist" || $_POST["job_title_NHS"] == "Psychotherapist" || $_POST["job_title_NHS"] == "Social Worker" || $_POST["job_title_NHS"] == "Technician" || $_POST["job_title_NHS"] == "Therapist" || $_POST["job_title_NHS"] == "Youth Worker") {
            $ucat2 = "SCITECH";
        }
        elseif ($_POST["job_title_NHS"] == "Accountant" || $_POST["job_title_NHS"] == "IT" || $_POST["job_title_NHS"] == "Interpreter" || $_POST["job_title_NHS"] == "Librarian" || $_POST["job_title_NHS"] == "Manager" || $_POST["job_title_NHS"] == "Personal Assistant" || $_POST["job_title_NHS"] == "Receptionist" || $_POST["job_title_NHS"] == "Secretary") {
            $ucat2 = "ADMINCLER";
        }
        elseif ($_POST["job_title_NHS"] == "Dietitian" || $_POST["job_title_NHS"] == "Art" || $_POST["job_title_NHS"] == "Audiologist" || $_POST["job_title_NHS"] == "Chiropodist" || $_POST["job_title_NHS"] == "Drama" || $_POST["job_title_NHS"] == "Hearing" || $_POST["job_title_NHS"] == "Music" || $_POST["job_title_NHS"] == "Occupational" || $_POST["job_title_NHS"] == "Orthoptist" || $_POST["job_title_NHS"] == "Orthotist" || $_POST["job_title_NHS"] == "Paramedic Consultant" || $_POST["job_title_NHS"] == "Physiotherapist" || $_POST["job_title_NHS"] == "Podiatrist" || $_POST["job_title_NHS"] == "Prosthetist" || $_POST["job_title_NHS"] == "Radiographer" || $_POST["job_title_NHS"] == "Rehabilitation" || $_POST["job_title_NHS"] == "Speech") {
            $ucat2 = "AHP";
        }
        elseif ($_POST["job_title_NHS"] == "Domestic" || $_POST["job_title_NHS"] == "Driver" || $_POST["job_title_NHS"] == "Electrician" || $_POST["job_title_NHS"] == "Engineer" || $_POST["job_title_NHS"] == "Gardener" || $_POST["job_title_NHS"] == "Housekeeper" || $_POST["job_title_NHS"] == "Plumber" || $_POST["job_title_NHS"] == "Porter") {
            $ucat2 = "ESTATES";
        }
        elseif ($_POST["job_title_NHS"] == "Consultant" || $_POST["job_title_NHS"] == "Dental surgeon" || $_POST["job_title_NHS"] == "Doctor" || $_POST["job_title_NHS"] == "GP" || $_POST["job_title_NHS"] == "Observer") {
            $ucat2 = "MEDICDEN";
        }
        elseif ($_POST["job_title_NHS"] == "Midwife" || $_POST["job_title_NHS"] == "Nurse" || $_POST["job_title_NHS"] == "Staff Nurse") {
            $ucat2 = "NURSEMIDW";
        }
        elseif ($_POST["job_title_NHS"] == "Student Chiropodist" || $_POST["job_title_NHS"] == "Student Dietitian" || $_POST["job_title_NHS"] == "Student Health Visitor" || $_POST["job_title_NHS"] == "Student Midwife" || $_POST["job_title_NHS"] == "Student Nurse" || $_POST["job_title_NHS"] == "Student Nurse Mental" || $_POST["job_title_NHS"] == "Student Occupational Therapist" || $_POST["job_title_NHS"] == "Student Operating" || $_POST["job_title_NHS"] == "Student Orthoptist" || $_POST["job_title_NHS"] == "Student Physiotherapist" || $_POST["job_title_NHS"] == "Student Psychotherapist" || $_POST["job_title_NHS"] == "Student Radiographer" || $_POST["job_title_NHS"] == "Student Speech") {
            $ucat2 = "STUDENTS";
        }
        if ($_POST["job_title_NHS"] == "Biomedical Scientist" || $_POST["job_title_NHS"] == "Clinical Scientist") {
            $ucat3 = "CLINSCI";
        }
        elseif ($_POST["job_title_NHS"] == "Consultant" || $_POST["job_title_NHS"] == "Dental surgeon") {
            $ucat3 = "CONSULTANT";
        }
        elseif ($_POST["job_title_NHS"] == "Dietitian" || $_POST["job_title_NHS"] == "Student Dietitian") {
            $ucat3 = "DIETICIAN";
        }
        elseif ($_POST["job_title_NHS"] == "Doctor" || $_POST["job_title_NHS"] == "Observer") {
            $ucat3 = "DOCTOR";
        }
        elseif ($_POST["job_title_NHS"] == "GP") {
            $ucat3 = "GP";
        }
        elseif ($_POST["job_title_NHS"] == "Healthcare") {
            $ucat3 = "HCA";
        }
        elseif ($_POST["job_title_NHS"] == "Manager") {
            $ucat3 = "MANAGER";
        }
        elseif ($_POST["job_title_NHS"] == "Midwife" || $_POST["job_title_NHS"] == "Student Midwife") {
            $ucat3 = "MIDWIFE";
        }
        elseif ($_POST["job_title_NHS"] == "Nurse" || $_POST["job_title_NHS"] == "Nursery" || $_POST["job_title_NHS"] == "Staff Nurse" || $_POST["job_title_NHS"] == "Student Nurse" || $_POST["job_title_NHS"] == "Student Nurse Mental") {
            $ucat3 = "NURSE";
        }
        elseif ($_POST["job_title_NHS"] == "Occupational" || $_POST["job_title_NHS"] == "Student Occupational Therapist") {
            $ucat3 = "OCCTHER";
        }
        elseif ($_POST["job_title_NHS"] == "Counsellor" || $_POST["job_title_NHS"] == "Dental Surgery Assistant" || $_POST["job_title_NHS"] == "Laboratory" || $_POST["job_title_NHS"] == "Medical Laboratory Assistant" || $_POST["job_title_NHS"] == "Phlebotomist" || $_POST["job_title_NHS"] == "Social Care" || $_POST["job_title_NHS"] == "Chaplain" || $_POST["job_title_NHS"] == "Optometrist" || $_POST["job_title_NHS"] == "Osteopath" || $_POST["job_title_NHS"] == "Social Worker" || $_POST["job_title_NHS"] == "Therapist" || $_POST["job_title_NHS"] == "Youth Worker" || $_POST["job_title_NHS"] == "Accountant" || $_POST["job_title_NHS"] == "IT" || $_POST["job_title_NHS"] == "Interpreter" || $_POST["job_title_NHS"] == "Librarian" || $_POST["job_title_NHS"] == "Art" || $_POST["job_title_NHS"] == "Audiologist" || $_POST["job_title_NHS"] == "Chiropodist" || $_POST["job_title_NHS"] == "Drama" || $_POST["job_title_NHS"] == "Hearing" || $_POST["job_title_NHS"] == "Music" || $_POST["job_title_NHS"] == "Orthoptist" || $_POST["job_title_NHS"] == "Orthotist" || $_POST["job_title_NHS"] == "Paramedic" || $_POST["job_title_NHS"] == "Paramedic Consultant" || $_POST["job_title_NHS"] == "Prosthetist" || $_POST["job_title_NHS"] == "Rehabilitation" || $_POST["job_title_NHS"] == "Domestic" || $_POST["job_title_NHS"] == "Driver" || $_POST["job_title_NHS"] == "Electrician" || $_POST["job_title_NHS"] == "Engineer" || $_POST["job_title_NHS"] == "Gardener" || $_POST["job_title_NHS"] == "Housekeeper" || $_POST["job_title_NHS"] == "Plumber" || $_POST["job_title_NHS"] == "Porter" || $_POST["job_title_NHS"] == "Student Chiropodist" || $_POST["job_title_NHS"] == "Student Health Visitor" || $_POST["job_title_NHS"] == "Student Orthoptist") {
            $ucat3 = "OTHERNHS";
        }
        elseif ($_POST["job_title_NHS"] == "Pre-reg Pharmacist" || $_POST["job_title_NHS"] == "Pharmacist") {
            $ucat3 = "PHARMACIST";
        }
        elseif ($_POST["job_title_NHS"] == "Physiotherapist" || $_POST["job_title_NHS"] == "Student Physiotherapist") {
            $ucat3 = "PHYSIO";
        }
        elseif ($_POST["job_title_NHS"] == "Associate Practitioner") {
            $ucat3 = "PRACT";
        }
        elseif ($_POST["job_title_NHS"] == "Assistant Psychologist" || $_POST["job_title_NHS"] == "Assistant Psychotherapist" || $_POST["job_title_NHS"] == "Clinical Psychologist" || $_POST["job_title_NHS"] == "Psychologist" || $_POST["job_title_NHS"] == "Psychotherapist" || $_POST["job_title_NHS"] == "Student Psychotherapist") {
            $ucat3 = "PSYCHOL";
        }
        elseif ($_POST["job_title_NHS"] == "Radiographer" || $_POST["job_title_NHS"] == "Student Radiographer") {
            $ucat3 = "RADIOG";
        }
        elseif ($_POST["job_title_NHS"] == "Receptionist") {
            $ucat3 = "RECEP";
        }
        elseif ($_POST["job_title_NHS"] == "Secretary" || $_POST["job_title_NHS"] == "Personal Assistant") {
            $ucat3 = "SECRETARY";
        }
        elseif ($_POST["job_title_NHS"] == "Speech" || $_POST["job_title_NHS"] == "Student Speech") {
            $ucat3 = "SPEECHTHER";
        }
        elseif ($_POST["job_title_NHS"] == "Assistant Technician" || $_POST["job_title_NHS"] == "Student Technician" || $_POST["job_title_NHS"] == "Technician") {
            $ucat3 = "TECHNICIAN";
        }
        elseif ($_POST["job_title_NHS"] == "Operating Department Practitioner" || $_POST["job_title_NHS"] == "Student Operating") {
            $ucat3 = "ODP";
        }
    }
    elseif($user_group == "CLLOCAL") {
        $first_name = test_input($_POST["first_name_CLLOCAL"]);
        $last_name = test_input($_POST["last_name_CLLOCAL"]);
        $password = xxxxxxxxxxxxxx;
        $campus_code = test_input($_POST["campus_code_CLLOCAL"]);
        $birth_date = date("Y-m-d", strtotime($_POST["birth_date_CLLOCAL"])). "Z";
        $expiry_date = date("Y-m-d", strtotime("+1 year")). "Z";
        $line1 = test_input($_POST["line1_CLLOCAL"]);
        $line2 = test_input($_POST["line2_CLLOCAL"]);
        $city = test_input($_POST["city_CLLOCAL"]);
        $postcode = test_input($_POST["postcode_CLLOCAL"]);
        $email_address = test_input($_POST["email_address_CLLOCAL"]);
        $phone_number = test_input($_POST["phone_number_CLLOCAL"]);
        $ucat1 = test_input($_POST["ucat1_CLLOCAL"]);
    }
    elseif($user_group == "ALLALUMNUS") {
        $first_name = test_input($_POST["first_name_ALUMNUS"]);
        $last_name = test_input($_POST["last_name_ALUMNUS"]);
        $password = xxxxxxxxxxxxxx;
        $campus_code = test_input($_POST["campus_code_ALUMNUS"]);
        $birth_date = date("Y-m-d", strtotime($_POST["birth_date_ALUMNUS"])). "Z";
        $expiry_date = date("Y-m-d", strtotime("+1 year")). "Z";
        $line1 = test_input($_POST["line1_ALUMNUS"]);
        $line2 = test_input($_POST["line2_ALUMNUS"]);
        $city = test_input($_POST["city_ALUMNUS"]);
        $postcode = test_input($_POST["postcode_ALUMNUS"]);
        $email_address = test_input($_POST["email_address_ALUMNUS"]);
        $phone_number = test_input($_POST["phone_number_ALUMNUS"]);
        $ucat1 = test_input($_POST["ucat1_ALUMNUS"]);
        $ucat2 = "ALUMNUS";
        $note1 = "Completed studies in ".$_POST["study_year_ALUMNUS"];
    }
    elseif($user_group == "CLREF") {
        $first_name = test_input($_POST["first_name_CLREF"]);
        $last_name = test_input($_POST["last_name_CLREF"]);
        $password = xxxxxxxxxxxxxx;
        $campus_code = test_input($_POST["campus_code_CLREF"]);
        $birth_date = date("Y-m-d", strtotime($_POST["birth_date_CLREF"])). "Z";
        $expiry_date = date("Y-m-d", strtotime("+1 year")). "Z";
        $line1 = test_input($_POST["line1_CLREF"]);
        $line2 = test_input($_POST["line2_CLREF"]);
        $city = test_input($_POST["city_CLREF"]);
        $postcode = test_input($_POST["postcode_CLREF"]);
        $email_address = test_input($_POST["email_address_CLREF"]);
        $phone_number = test_input($_POST["phone_number_CLREF"]);
        $note1 = test_input($_POST["library_use_CLREF"]);
        $ucat1 = test_input($_POST["ucat1_CLREF"]);
        if ($_POST["ref_access"] == "work") {
            $ucat2 = "ACCOMM";
        }
        elseif($_POST["ref_access"] == "academic") {
            $ucat2 = "ACPUBLIC";
        }
    }
 
# PHP to create XML matching Ex Libris' user schema
# https://developers.exlibrisgroup.com/alma/apis/xsd/rest_user.xsd?tags=POST

    $corexml1="<?xml version='1.0' encoding='UTF-8' standalone='yes'?>
        <user>
        <record_type>PUBLIC</record_type>
        <primary_id>".$primary_id."</primary_id>
        <first_name>".$first_name."</first_name>
        <last_name>".$last_name."</last_name>
        <user_group>".$user_group."</user_group>
        <campus_code>".$campus_code."</campus_code>
        <preferred_language>en</preferred_language>";
    if ($birth_date == "1970-01-01Z"){
        $birthdatexml="";
    }
    else {
        $birthdatexml="<birth_date>".$birth_date."</birth_date>";
    }
    $corexml2="<expiry_date>".$expiry_date."</expiry_date>
        <purge_date>2021-01-20Z</purge_date>
        <account_type>INTERNAL</account_type>
        <password>".$password."</password>
        <status>ACTIVE</status>
        <rs_libraries>
            <rs_library>
                <code>".$campus_code."</code>
            </rs_library>
        </rs_libraries>";
    if (empty($line1)){
        $addressxml="";
    }
    else {
    $addressxml="<address>
                    <line1>".$line1."</line1>
                    <line2>".$line2."</line2>
                    <city>".$city."</city>
                    <state_province/>
                    <postal_code>".$postcode."</postal_code>
                    <country/>
                    <address_note/>
                    <start_date>".$start_date."</start_date>
                    <address_types>
                        <address_type>home</address_type>
                     </address_types>
                </address>";
    }
    if (empty($department)){
        $addressnhsxml="";
    }
    else {
    $addressnhsxml="<address>
                    <line1>".$department."</line1>
                    <line2>".$campus_code." Hospital</line2>
                    <city>London</city>
                    <state_province/>
                    <postal_code/>
                    <country>UK</country>
                    <address_note/>
                    <start_date>".$start_date."</start_date>
                    <address_types>
                        <address_type>work</address_type>
                     </address_types>
                </address>";
    }
    if (empty($email_address)){
        $emailxml="";
    }
    else {
    $emailxml="<emails>
                <email preferred='true'>
                <email_address>".$email_address."</email_address>
                <email_types>
                    <email_type>personal</email_type>
                </email_types>
                </email>
            </emails>";
    }
    
    if (empty($phone_number)){
        $phonexml="";
    }
    else {
    $phonexml="<phones>
                <phone preferred='true'>
                <phone_number>".$phone_number."</phone_number>
                <phone_types>
                    <phone_type>office</phone_type>
                </phone_types>
                </phone>
            </phones>";
    }
    
    if (empty($note1)){
        $notesxml="<user_notes>
                <user_note>
                    <note_type>CIRCULATION</note_type>
                    <note_text>Added from online registration form on ".$start_date."</note_text>
                    <user_viewable>true</user_viewable>
                </user_note>
                </user_notes>";
    }
    else {
    $notesxml="<user_notes>
                <user_note>
                    <note_type>POPUP</note_type>
                    <note_text>".$note1."</note_text>
                    <user_viewable>true</user_viewable>
                    </user_note>
                <user_note>
                    <note_type>CIRCULATION</note_type>
                    <note_text>Added from online registration form on ".$start_date."</note_text>
                    <user_viewable>true</user_viewable>
                </user_note>
                </user_notes>";
    }
    
    if (empty($ucat1)){
        $ucat1xml="";
    }
    else {
    $ucat1xml="<user_statistic>
                <statistic_category>".$ucat1."</statistic_category>
                <category_type>UCAT1</category_type>
            </user_statistic>";
    }
    
    if (empty($ucat2)){
        $ucat2xml="";
    }
    else {
    $ucat2xml="<user_statistic>
                <statistic_category>".$ucat2."</statistic_category>
                <category_type>UCAT2</category_type>
            </user_statistic>";
    }
    
    if (empty($ucat3)){
        $ucat3xml="";
    }
    else {
    $ucat3xml="<user_statistic>
                <statistic_category>".$ucat3."</statistic_category>
                <category_type>UCAT3</category_type>
            </user_statistic>";
    }
    # assemble XML from component parts
    $xml=$corexml1 . $birthdatexml . $corexml2 . "<contact_info>" . "<addresses>" . $addressxml . $addressnhsxml . "</addresses>" . $emailxml . $phonexml . "</contact_info>" . $notesxml . "<user_statistics>" . $ucat1xml . $ucat2xml . $ucat3xml . "</user_statistics>" . "</user>";

# submit XML via cURL to Alma's 'create user' function in the API
# https://developers.exlibrisgroup.com/alma/apis/users/POST/gwPcGly021r0XQMGAttqcNyT3YiaSYVA/0aa8d36f-53d6-48ff-8996-485b90b103e4
#    $ch = curl_init();
#    $url = $almaurl.'/almaws/v1/users';
#    $queryParams = '?' . urlencode('apikey') . '=' . urlencode($apikey);
#    curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
#    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
#    curl_setopt($ch, CURLOPT_HEADER, FALSE);
#    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
#    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
#    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
#    $response = curl_exec($ch);
#    curl_close($ch);
#    var_dump($response);
#    echo $response;

#submit XML (not using cURL) to Alma's 'create user' function in the API
# https://developers.exlibrisgroup.com/alma/apis/users/POST/gwPcGly021r0XQMGAttqcNyT3YiaSYVA/0aa8d36f-53d6-48ff-8996-485b90b103e4

    $url = $almaurl.'/almaws/v1/users';
    $queryParams = '?' . urlencode('apikey') . '=' . urlencode($apikey);
    $fullurl = $url.$queryParams;
    $stream_options = array(
        'http' => array(
            'method'  => 'POST',
            'header'  => 'Content-Type: application/xml' . "\r\n",
            'content' => $xml));

    $context  = stream_context_create($stream_options);
    $response = file_get_contents($fullurl, null, $context);
    
# temporary test lines to display response after submission
#    var_dump($response);
#    echo $response;

# display success message
    if (isset($response)){ 
        if ($user_group == "ALLALUMNUS"){
            $message="Thank you for your application for alumni membership at Imperial College Library. A copy of this message has been sent to the email address provided.<br /><br />Your assigned username is " . $primary_id . "<br /><br />To complete your application please visit the Welcome Desk at <a href='https://www.imperial.ac.uk/admin-services/library/use-the-library/our-libraries/'>Central Library</a> or at one of our <a href='https://www.imperial.ac.uk/admin-services/library/use-the-library/our-libraries/'>campus libraries</a>. Please bring photographic ID and proof of your current address.<br /><br />To apply for alumni membership you must first apply to the Alumni Office for an online <a href='https://www.imperial.ac.uk/alumni/SSLPage.aspx?pid=1990&tab=1'>interactive alumni services account</a>. You will be asked to log in to the account when you visit the Welcome Desk so please have your username and password ready.  It can take several days to receive your log-in for your online account so don't leave it until just before visiting the Library.";
        }
        elseif ($user_group == "CLLOCAL"){
            $message="Thank you for requesting Imperial College Library membership under the local institutions scheme. A copy of this message has been sent to the email address provided.<br /><br />Your assigned username is " . $primary_id . "<br /><br />To complete your membership application, please visit the Welcome Desk at Central Library and bring proof of your status at your home institution (ID card or letter on headed notepaper) show one of the <a href='https://www.imperial.ac.uk/admin-services/library/use-the-library/membership-and-borrowing/identification/'>required forms of identification</a>.<br /><br />If you have any questions about your application please email <a href='mailto:library@imperial.ac.uk'>library@imperial.ac.uk</a> or call 020 7594 8810.";
        }
        elseif ($user_group == "CLREF"){
            $message="Thank you for your recent application for Imperial College Library membership. A copy of this message has been sent to the email address provided.<br /><br />Your assigned username is " . $primary_id . "<br /><br />Your application will now be reviewed by library staff and we will contact you to let you know if it has been successful. Please do not visit the Library before you have received a further confirmation email. We aim to assess all applications within 5 working days.<br /><br />If approved, you will need to visit the Welcome Desk at Central Library show one of the <a href='https://www.imperial.ac.uk/admin-services/library/use-the-library/membership-and-borrowing/identification/'>required forms of identification</a>.<br /><br />If you have any questions about your application please email <a href='mailto:library@imperial.ac.uk'>library@imperial.ac.uk</a> or call 020 7594 8810.";
        }
        elseif ($user_group == "NHS" || $user_group == "MEDREF" || $user_group == "NHSPS" || $user_group == "CWREF" || $user_group == "CXREF" || $user_group == "HHREF" || $user_group == "RBREF" || $user_group == "SMREF" || $user_group == "CWLOCAL" || $user_group == "CXLOCAL" || $user_group == "HHLOCAL" || $user_group == "RBLOCAL" || $user_group == "SMLOCAL" || $user_group == "STAFFIC"){
            $message="Thank you for requesting Imperial College Library membership under the NHS membership scheme. A copy of this message has been sent to the email address provided.<br /><br />Your assigned username is " . $primary_id . "<br /><br />To complete your application, please visit your <a href='https://www.imperial.ac.uk/admin-services/library/use-the-library/our-libraries/'>chosen home library</a>, bringing with you your NHS trust ID card. If you do not have an ID card from your trust, please bring alternative proof of eligibility, for instance a university ID card for students on medical placement, or letter on headed note paper.<br /><br />If you have any questions about your application you can email <a href='mailto:library@imperial.ac.uk'>library@imperial.ac.uk</a> or call your chosen library on the phone numbers available <a href='https://www.imperial.ac.uk/admin-services/library/use-the-library/our-libraries/'>at this link</a>.";
        }
        elseif ($user_group == "SRXLOAN"){
            $message="Thank you for requesting Imperial College Library membership under the SCONUL Access Scheme. A copy of this message has been sent to the email address provided.<br /><br />Your assigned username is " . $primary_id . "<br /><br />Please visit the Welcome Desk at <a href='https://www.imperial.ac.uk/admin-services/library/use-the-library/our-libraries/'>Central Library</a> or at one of our <a href='https://www.imperial.ac.uk/admin-services/library/use-the-library/our-libraries/'>campus libraries</a>. Please bring with you:<ol><li>Your SCONUL Access confirmation email (Band A)</li><li>A valid ID card from your home institution. If your ID card does not include your photo, you must bring additional photographic ID</li><li>Proof of your current address</li><li>A current email address at your home institution's domain (not Gmail, etc.)</li><br /><br />Please note Imperial College Library only accepts SCONUL Access members in band A of the scheme (academic staff and PhD level researchers). Undergraduates and taught postgraduates can make use of the SCONUL Vacation Access Scheme during Imperial College vacation periods.<br /><br />If you have any questions about your application please email <a href='mailto:library@imperial.ac.uk'>library@imperial.ac.uk</a> or call 020 7594 8810.";
        }
        elseif ($user_group == "ICREF"){
            $message="Thank you for requesting Imperial College Library membership under the SCONUL Vacation Access Scheme. A copy of this message has been sent to the email address provided.<br /><br />Your assigned username is " . $primary_id . "<br /><br />Please visit your <a href='https://www.imperial.ac.uk/admin-services/library/use-the-library/our-libraries/'>chosen library</a> during an Imperial College Christmas or summer vacation period and bring a valid ID card from your home institution.<br /><br />If you have any questions about your application please email <a href='mailto:library@imperial.ac.uk'>library@imperial.ac.uk</a> or call 020 7594 8810.";
        }
        echo "<div class='bootstrap-frm'>" . $message . "</div>";
        
        # send a success email to the user
        $to = $email_address; // this is your Email address
        $cc = "your.email@email.com"; // this is the sender's Email address
        $subject = "Imperial College Library membership application";
        $subject2 = "Copy of your form submission";
        $message1 = "Dear " . $first_name . " " . $last_name . "<br /><br />" . $message . "<br /><br />" . "Kind regards," . "<br /><br />" . "Library Services";
        $message2 = "A user application form for user group " . $user_group . " was submitted by " . $first_name . " " . $last_name . ":" . "<br /><br />" . $message1 . "<br /><br />Response from Alma: <br /><br />" . $response;

        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= "From:" . $cc;
        $headers2 = 'MIME-Version: 1.0' . "\r\n";
        $headers2 .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers2 .= "From:" . $to;
        mail($to,$subject,$message1,$headers);
        mail($cc,$subject2,$message2,$headers2); // sends a copy of the message to the sender
    }

# temporary test lines to display XML as it would be submitted
#    $xml = str_replace('&', '&amp;', $xml);
#    $xml = str_replace('<', '&lt;', $xml);
#    echo '<pre>' . $xml . '</pre>';

    header('Location: reg_form_end.html');
    exit();
?>
