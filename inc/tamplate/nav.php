<?php

?>
<p>
<div class="nav">
    <a class="nav-link" href="/crud1/index.php?task=report">All Students</a>
    <?php
    if ($role == 'admin') { ?>
        <a class="nav-link" href="/crud1/index.php?task=add">Add New Student</a>
        <a class="nav-link" href="/crud1/index.php?task=seed">Seed</a>
        <?php }
    if (count($_SESSION) > 0) {
        if (true == $_SESSION['loggedin']) {
        ?>
            <a class="nav-link" href="/crud1/auth.php?logout=1">Logout</a>
        <?php }
    } else { ?>
        <a class="nav-link" href="/crud1/auth.php?task=register">Register</a>
        <a class="nav-link" href="/crud1/auth.php?task=login">Login</a>
    <?php
    }
    ?>
</div>
</p>