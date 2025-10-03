<?php
session_start();
include("../db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type']; // dreamer, mentor, company, investor

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    switch ($user_type) {
        case 'dreamer':
            $fullName = $_POST['fullName'];
            $bio = $_POST['bio'];
            $country = $_POST['country'];
            $goal = $_POST['goal'];
            $field_of_interest = $_POST['field_of_interest'];
            $skills = $_POST['skills'];
            $opportunity_seeking = $_POST['opportunity_seeking'];
            $number = $_POST['number'];

            $sql = "INSERT INTO dreamers (user_id, fullName, bio, country, goal, field_of_interest, skills, opportunity_seeking, number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issssiiis", $user_id, $fullName, $bio, $country, $goal, $field_of_interest, $skills, $opportunity_seeking, $number);
            break;

        case 'mentor':
            $fullName = $_POST['fullName'];
            $bio = $_POST['bio'];
            $country = $_POST['country'];   
            $expertise = $_POST['expertise'];
            $years_experience = $_POST['years_experience'];
            $mentee_focus = $_POST['mentee_focus'];
            $support_type = $_POST['support_type'];
            $phone = $_POST['phone'];

            $sql = "INSERT INTO mentors (user_id, fullName, bio, country, expertise, years_experience, mentee_focus, support_type, phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issssisis", $user_id, $fullName, $bio, $country, $expertise, $years_experience, $mentee_focus, $support_type, $phone);
            break;

        case 'investor':
            $fullName_or_organization = $_POST['fullName_or_organization'];
            $bio = $_POST['bio'];
            $country = $_POST['country'];
            $preferred_industries = $_POST['preferred_industries'];
            $investment_stage = $_POST['investment_stage'];
            $investment_range = $_POST['investment_range'];
            $investment_type = $_POST['investment_type'];
            $investment_focus = $_POST['investment_focus'];
            $phone = $_POST['phone'];
            $website = $_POST['website'];

            $sql = "INSERT INTO investors (user_id, fullName_or_organization, bio, country, preferred_industries, investment_stage, investment_range, investment_type, investment_focus, phone, website) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isssssssss", $user_id, $fullName_or_organization, $bio, $country, $preferred_industries, $investment_stage, $investment_range, $investment_type, $investment_focus, $phone, $website);
            break;

        case 'company':
            $company_name = $_POST['company_name'];
            $bio = $_POST['bio'];
            $country = $_POST['country'];
            $company_field = $_POST['company_field'];
            $opportunity_offered = $_POST['opportunity_offered'];
            $skills_talent_interested_in = $_POST['skills_talent_interested_in'];
            $type = $_POST['type'];
            $phone = $_POST['phone'];
            $website = $_POST['website'];
            $link = $_POST['link'];

            $sql = "INSERT INTO companies (user_id, company_name, bio, country, company_field, opportunity_offered, skills_talent_interested_in, type, website, link) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isssssssss", $user_id, $company_name, $bio, $country, $company_field, $opportunity_offered, $skills_talent_interested_in, $type, $website, $link);
            break;
    }

    if ($stmt->execute()) {
        // Mark profile as complete
        $update = "UPDATE users SET profile_complete = 1 WHERE id = ?";
        $up = $conn->prepare($update);
        $up->bind_param("i", $user_id);
        $up->execute();

        header("Location: ../dashboard.php");
        exit;
    } else {
        echo "Error saving profile.";
    }
}
?>
