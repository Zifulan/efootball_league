<?php
// generate_fixture.php (updated)
$host = 'localhost';
$db = 'czkugpvg_league';
$user = 'czkugpvg_league';
$pass = 'BangJal!';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB Connection Failed: " . $e->getMessage());
}

$league_id = $_GET['league_id'] ?? null;
if (!$league_id) {
    die("Missing league_id.");
}

// Fetch all teams
$teamStmt = $pdo->prepare("SELECT team_name FROM league_standings WHERE league_id = ?");
$teamStmt->execute([$league_id]);
$teams = $teamStmt->fetchAll(PDO::FETCH_COLUMN);

// Check highest round number
$maxRoundStmt = $pdo->prepare("SELECT MAX(round) FROM league_fixtures WHERE league_id = ?");
$maxRoundStmt->execute([$league_id]);
$current_round = (int)$maxRoundStmt->fetchColumn() ?: 1;

for ($i = 0; $i < count($teams); $i++) {
    for ($j = $i + 1; $j < count($teams); $j++) {
        $home = $teams[$i];
        $away = $teams[$j];

        // Check if this pair already exists (home and away)
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM league_fixtures WHERE league_id = ? AND ((home_team = ? AND away_team = ?) OR (home_team = ? AND away_team = ?))");
        $checkStmt->execute([$league_id, $home, $away, $away, $home]);
        if ($checkStmt->fetchColumn() >= 2) continue; // skip if both matches exist

        // Add missing leg(s)
        if (!fixtureExists($pdo, $league_id, $home, $away)) {
            insertFixture($pdo, $league_id, ++$current_round, $home, $away);
        }
        if (!fixtureExists($pdo, $league_id, $away, $home)) {
            insertFixture($pdo, $league_id, ++$current_round, $away, $home);
        }
    }
}

function fixtureExists($pdo, $league_id, $home, $away) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM league_fixtures WHERE league_id = ? AND home_team = ? AND away_team = ?");
    $stmt->execute([$league_id, $home, $away]);
    return $stmt->fetchColumn() > 0;
}

function insertFixture($pdo, $league_id, $round, $home, $away) {
    $stmt = $pdo->prepare("INSERT INTO league_fixtures (league_id, round, home_team, away_team, home_score, away_score, is_played)
                           VALUES (?, ?, ?, ?, 0, 0, 0)");
    $stmt->execute([$league_id, $round, $home, $away]);
}

echo "Fixtures generated successfully.";