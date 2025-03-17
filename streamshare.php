<?php
require_once 'connect.php';
require_once 'user_status.php';

// Get episode ID from URL if provided
$episode_id = isset($_GET['episode_id']) ? (int)$_GET['episode_id'] : 0;

if ($episode_id > 0) {
    // Show specific episode servers
    $query = $db->prepare("SELECT e.*, a.name as anime_name FROM episodes e 
                          JOIN anime a ON e.anime_id = a.id 
                          WHERE e.id = :id");
    $query->bindParam(":id", $episode_id);
    $query->execute();
    $episode = $query->fetch(PDO::FETCH_ASSOC);

    if (!$episode) {
        echo "<div class='container mt-5'><div class='alert alert-danger'>Episode not found!</div></div>";
        require_once 'footer.php';
        exit;
    }

    // Get all servers for this episode
    $query = $db->prepare("SELECT * FROM servers WHERE episode_id = :episode_id AND is_active = 1 ORDER BY server_name");
    $query->bindParam(":episode_id", $episode_id);
    $query->execute();
    $servers = $query->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Show all anime with their episodes
    $query = $db->prepare("SELECT a.*, e.id as episode_id, e.episode_nr FROM anime a 
                          LEFT JOIN episodes e ON a.id = e.anime_id 
                          ORDER BY a.name, e.episode_nr");
    $query->execute();
    $episodes = $query->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Stream & Share - Netfixed</title>
    <link rel="icon" type="image/x-icon" href="assets/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="./css/style.css" />
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg mb-3 p-2">
            <div class="container-fluid">
                <a class="navbar-brand mt-2 mt-lg-0" href="userpage.php">
                    <h4 class="m-0">Netfixed</h4>
                </a>
                <div class="dropdown">
                    <a class="dropdown d-flex align-items-center hidden-arrow" href="#" id="navbarDropdownMenuAvatar" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?php echo $currentProfilePicture ?>" class="rounded-circle" height="55" width="55" alt="Profile Picture" loading="lazy" />
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar">
                        <li><a class="dropdown-item" href="profile.php">My profile</a></li>
                        <li><a class="dropdown-item" href="edit_profile.php">Settings</a></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="container mt-5">
        <?php if ($episode_id > 0): ?>
            <!-- Single Episode View -->
            <div class="row">
                <div class="col-12">
                <h2 class="mb-4"><?php echo htmlspecialchars($episode['anime_name']); ?> - Episode <?php echo htmlspecialchars($episode['episode_nr']); ?></h2>

                        <!-- Larger iframe container -->
                        <div class="video-container mb-4">
                            <div class="ratio ratio-16x9 border " style="min-height: 70vh;">
                                <iframe id="videoFrame" 
                                        src="<?php echo htmlspecialchars($servers[0]['server_url']); ?>" 
                                        allowfullscreen 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                                </iframe>
                            </div>
                        </div>
                        <!-- Server selection buttons -->
                        <div class="d-flex justify-content-between align-items-center w-100 mb-4">
                        <div class="btn-group">
                                <?php foreach ($servers as $index => $server): ?>
                                    <button class="btn btn-outline-primary server-btn <?php echo $index === 0 ? 'active' : ''; ?>"
                                            data-server-url="<?php echo htmlspecialchars($server['server_url']); ?>">
                                        <?php echo htmlspecialchars($server['server_name']); ?>
                                    </button>
                                <?php endforeach; ?>
                        </div>
                    <?php if (!empty($servers)): ?>
                        <!-- Add episode navigation -->
                        <div>
                            <?php
                            // Get previous and next episodes
                            $prevEp = $db->prepare("SELECT id, episode_nr FROM episodes WHERE anime_id = :anime_id AND episode_nr < :current_ep ORDER BY episode_nr DESC LIMIT 1");
                            $prevEp->execute([':anime_id' => $episode['anime_id'], ':current_ep' => $episode['episode_nr']]);
                            $prevEpisode = $prevEp->fetch(PDO::FETCH_ASSOC);

                            $nextEp = $db->prepare("SELECT id, episode_nr FROM episodes WHERE anime_id = :anime_id AND episode_nr > :current_ep ORDER BY episode_nr ASC LIMIT 1");
                            $nextEp->execute([':anime_id' => $episode['anime_id'], ':current_ep' => $episode['episode_nr']]);
                            $nextEpisode = $nextEp->fetch(PDO::FETCH_ASSOC);
                            ?>
                            
                            <?php if ($prevEpisode): ?>
                                <a href="?episode_id=<?php echo $prevEpisode['id']; ?>" class="btn btn-primary">← Episode <?php echo $prevEpisode['episode_nr']; ?></a>
                            <?php endif; ?>
                            
                            <?php if ($nextEpisode): ?>
                                <a href="?episode_id=<?php echo $nextEpisode['id']; ?>" class="btn btn-primary">Episode <?php echo $nextEpisode['episode_nr']; ?> →</a>
                            <?php endif; ?>
                        </div>
                       
                    <?php else: ?>
                        <div class="alert alert-info">
                            No streaming servers available for this episode at the moment.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <!-- All Episodes View -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Stream & Share</h2>
                <div class="w-300">
                    <input type="text" id="animeSearch" 
                           class="form-control bg-dark border-secondary text-white" 
                           placeholder="Search anime...">
                </div>
            </div>
            <div class="row" id="animeGrid">
                <?php 
                $current_anime = '';
                $anime_episodes = [];
                
                // First, organize episodes by anime
                foreach ($episodes as $episode) {
                    if (!isset($anime_episodes[$episode['name']])) {
                        $anime_episodes[$episode['name']] = [
                            'image' => $episode['image'],
                            'episodes' => []
                        ];
                    }
                    $anime_episodes[$episode['name']]['episodes'][] = $episode;
                }
                
                // Then display each anime and its episodes
                foreach ($anime_episodes as $anime_name => $anime_data):
                ?>
                    <div class="col-md-4 mb-4 anime-card transition-transform" data-anime-name="<?php echo strtolower(htmlspecialchars($anime_name)); ?>">
                        <div class="card h-100 bg-dark border-0">
                            <div class="card-header bg-dark text-white p-0">
                                <div class="position-relative">
                                    <img src="assets/img/<?php echo htmlspecialchars($anime_data['image']); ?>" 
                                         class="card-img-top object-fit-cover" 
                                         alt="<?php echo htmlspecialchars($anime_name); ?>"
                                         style="height: 300px;">
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-primary">
                                            <?php echo count($anime_data['episodes']); ?> Episodes
                                        </span>
                                    </div>
                                </div>
                                <div class="p-2">
                                    <h3 class="card-title mb-0 text-center fs-5"><?php echo htmlspecialchars($anime_name); ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <style>
        .transition-transform {
            transition: transform 0.2s;
        }
        .transition-transform:hover {
            transform: translateY(-5px);
        }
        .min-width-60 {
            min-width: 60px;
        }
        .w-300 {
            width: 300px;
        }
    </style>

    <script>
        document.getElementById('animeSearch').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.anime-card');
            
            cards.forEach(card => {
                const animeName = card.dataset.animeName;
                if (animeName.includes(searchTerm)) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 