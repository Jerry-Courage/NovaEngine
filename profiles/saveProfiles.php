<?php
session_start();
include("../db.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type']; // dreamer, mentor, company, investor

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    // Prepare data safely
    function clean($data) {
        return trim(htmlspecialchars($data ?? ''));
    }

    switch ($user_type) {
        // ---------------- DREAMER ----------------
        case 'dreamer':
            $fullName = clean($_POST['fullName']);
            $bio = clean($_POST['bio']);
            $country = clean($_POST['country']);
            $goal = clean($_POST['goal']);
            $field_of_interest = clean($_POST['field_of_interest']);
            $skills = clean($_POST['skills']);
            $opportunity_seeking = clean($_POST['opportunity_seeking']);
            $number = clean($_POST['number']);

            $sql = "INSERT INTO dreamers (user_id, fullName, bio, country, goal, field_of_interest, skills, opportunity_seeking, number)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(
                "issssssss",
                $user_id, $fullName, $bio, $country, $goal,
                $field_of_interest, $skills, $opportunity_seeking, $number
            );
            break;

        // ---------------- MENTOR ----------------
        case 'mentor':
            $fullName = clean($_POST['fullName']);
            $bio = clean($_POST['bio']);
            $country = clean($_POST['country']);
            $expertise = clean($_POST['expertise']);
            $years_experience = clean($_POST['years_experience']);
            $mentee_focus = clean($_POST['mentee_focus']);
            $support_type = clean($_POST['support_type']);
            $phone = clean($_POST['phone']);

            $sql = "INSERT INTO mentors (user_id, fullName, bio, country, expertise, years_experience, mentee_focus, support_type, phone)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(
                "issssssss",
                $user_id, $fullName, $bio, $country,
                $expertise, $years_experience, $mentee_focus,
                $support_type, $phone
            );
            break;

        // ---------------- INVESTOR ----------------
        case 'investor':
            $fullName_or_organization = clean($_POST['fullName_or_organization']);
            $bio = clean($_POST['bio']);
            $country = clean($_POST['country']);
            $preferred_industries = clean($_POST['preferred_industries']);
            $investment_stage = clean($_POST['investment_stage']);
            $investment_range = clean($_POST['investment_range']);
            $investment_type = clean($_POST['investment_type']);
            $investment_focus = clean($_POST['investment_focus']);
            $phone = clean($_POST['phone']);
            $website = clean($_POST['website']);

            $sql = "INSERT INTO investors (user_id, fullName_or_organization, bio, country, preferred_industries, investment_stage, investment_range, investment_type, investment_focus, phone, website)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(
                "issssssssss",
                $user_id, $fullName_or_organization, $bio, $country,
                $preferred_industries, $investment_stage, $investment_range,
                $investment_type, $investment_focus, $phone, $website
            );
            break;

        // ---------------- COMPANY ----------------
        case 'company':
            $company_name = clean($_POST['company_name']);
            $bio = clean($_POST['bio']);
            $country = clean($_POST['country']);
            $company_field = clean($_POST['company_field']);
            $opportunity_offered = clean($_POST['opportunity_offered']);
            $skills_talent_interested_in = clean($_POST['skills_talent_interested_in']);
            $type = clean($_POST['type']);
            $phone = clean($_POST['phone']);
            $website = clean($_POST['website']);
            $link = clean($_POST['link']);

            $sql = "INSERT INTO companies (user_id, company_name, bio, country, company_field, opportunity_offered, skills_talent_interested_in, type, phone, website, link)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(
                "issssssssss",
                $user_id, $company_name, $bio, $country,
                $company_field, $opportunity_offered, $skills_talent_interested_in,
                $type, $phone, $website, $link
            );
            break;

        // ---------------- UNKNOWN USER TYPE ----------------
        default:
            die("Invalid user type specified.");
    }

    // Execute the query safely
    if ($stmt->execute()) {
        // Mark profile as complete
        $update = "UPDATE users SET profile_complete = 1 WHERE id = ?";
        $up = $conn->prepare($update);
        $up->bind_param("i", $user_id);
        $up->execute();

        // Clean up
        $stmt->close();
        $up->close();
        $conn->close();

        header("Location: ../dashboard.php");
        exit;
    } else {
        echo "❌ Error saving profile: " . htmlspecialchars($stmt->error);
    }
}
?>
