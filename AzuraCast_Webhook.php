<?php
session_start();

$envFile = "/path/to/env/file/webhook.env"; //Update to your actual secure path that you have stored your .env file at

//------------START-DO NOT TOUCH------------\\
if (!file_exists($envFile)) {
    http_response_code(500);
    die(json_encode(["error" => "Missing .env file"]));
}

$dotenv = parse_ini_file($envFile);
$secret_api_key = $dotenv["AZURACAST_WEBHOOK_KEY"] ?? "";
$discordWebhookUrl = $dotenv["DISCORD_WEBHOOK_URL"] ?? "";

if (!isset($_GET['key']) || $_GET['key'] !== $secret_api_key) {
    http_response_code(403);
    die(json_encode(["error" => "Invalid API Key"]));
}

$max_requests = 5;
$time_window = 60;
if (!isset($_SESSION['webhook_requests'])) $_SESSION['webhook_requests'] = [];
$_SESSION['webhook_requests'] = array_filter($_SESSION['webhook_requests'], fn($timestamp) => ($timestamp > time() - $time_window));
if (count($_SESSION['webhook_requests']) >= $max_requests) {
    http_response_code(429);
    die(json_encode(["error" => "Too many requests, slow down!"]));
}
$_SESSION['webhook_requests'][] = time();

$rawData = file_get_contents("php://input");
$webhookData = json_decode($rawData, true);

if (!$webhookData) {
    http_response_code(400);
    die(json_encode(["error" => "Invalid JSON payload"]));
}
//------------END-DO NOT TOUCH------------\\

// Extract & sanitize fields
$songTitle = htmlspecialchars(strip_tags($webhookData['now_playing']['song']['title'] ?? "Unknown Title"));
$songArtist = htmlspecialchars(strip_tags($webhookData['now_playing']['song']['artist'] ?? ""));
$streamerName = htmlspecialchars(strip_tags($webhookData['live']['streamer_name'] ?? ""));
$listenerUnique = intval($webhookData['listeners']['unique'] ?? 0);
$listenerTotal = intval($webhookData['listeners']['total'] ?? 0);
$publicPlayerUrl = htmlspecialchars($webhookData['station']['public_player_url'] ?? "#");
$albumArtUrl = htmlspecialchars($webhookData['now_playing']['song']['art'] ?? "https://your-url-here.tld/art.png"); // Default artwork if none provided
$timezone = htmlspecialchars(strip_tags($webhookData['station']['timezone'] ?? ""));

// These may require adjustments depending on your Azuracast setup.
// Specifically the 0 may be different for you.
$bitrate = intval($webhookData['station']['mounts'][0]['bitrate'] ?? 0); 
$format = htmlspecialchars(strip_tags($webhookData['station']['mounts'][0]['format'] ?? "Unknown"));

// Timezone Hell
if (empty($timezone)) {
    $timezone = "UTC";
}

try {
    $timezoneObject = new DateTimeZone($timezone);
} catch (Exception $e) {
    $timezoneObject = new DateTimeZone("UTC"); 
}

$datetime = new DateTime("now", $timezoneObject);
$timezoneAbbr = $datetime->format("T");

// Determine how to display artist/DJ information
if (!empty($songArtist) && !empty($streamerName)) {
    $artistDisplay = "by $songArtist \n(Live: $streamerName)";
} elseif (!empty($songArtist)) {
    $artistDisplay = "by $songArtist";
} elseif (!empty($streamerName)) {
    $artistDisplay = "with $streamerName";
} else {
    $artistDisplay = "by Unknown Artist";
}

// Determine stream status
$streamStatus = !empty($streamerName) ? "🔴 **LIVE:** $streamerName" : "🎵 **Auto-DJ**";

$embed = [ // Craft the Discord Embed
    "embeds" => [
        [
            "title" => "YOUR-RADIO-NAME-HERE", // Update to your station's brand name
            "url" => "https://your-site-here.com/",  // Comment out to disable clickable title
            "description" => "**Now Playing**\n*$songTitle*\n *$artistDisplay*", 
            "color" => 0x800080, // Embed highlight color
            "thumbnail" => ["url" => $albumArtUrl],
            "fields" => [
                [
                    "name" => "", // Invisible section
                    "value" => "᲼᲼", // These characters prevent Discord from collapsing the field
                    "inline" => false
                ],
                [
                    "name" => "📡 Stream Status",
                    "value" => $streamStatus, // Results of live vs. Auto-DJ check
                    "inline" => true
                ],
                [
                    "name" => "👥 Listeners",
                    "value" => "$listenerUnique unique, $listenerTotal connected - [Join]($publicPlayerUrl)",
                    "inline" => true
                ],
            ],
            "footer" => [
                "text" => "Bitrate: {$bitrate}kbps | Format: {$format} | Timezone: {$timezoneAbbr}",
                "icon_url" => "https://your-url-here.tld/logo.png" // Change to your logo or remove it
            ]
        ]
    ]
];

// **OPTIONAL: Add custom username and avatar
$webhookOptions = [];
$customUsername = "Webhook-Name-Here"; // Change this or set as "" to disable
$customAvatarUrl = "https://your-site-here.com/avatar.png"; // Change or set to "" to disable

if (!empty($customUsername)) {
    $webhookOptions["username"] = $customUsername;
}
if (!empty($customAvatarUrl)) {
    $webhookOptions["avatar_url"] = $customAvatarUrl;
}

$finalPayload = array_merge($webhookOptions, $embed);
$jsonPayload = json_encode($finalPayload);

$ch = curl_init($discordWebhookUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
curl_exec($ch);
curl_close($ch);

http_response_code(200);
echo json_encode(["status" => "Webhook Processed"]);
?>
