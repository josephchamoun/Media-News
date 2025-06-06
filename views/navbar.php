<?php
$currentPage = $_GET['page'] ?? 'home'; // $_GET['page'] hiyye betjib esem l page mn URL
?>
<link rel="stylesheet" href="/CSS/navbar.css">
<nav class="navbar">
  <div class="nav-container">
    <div class="logo"><a href="/?page=home">Media News</a></div>
    <ul class="nav-list">
      <li><a href="/?page=home" class="<?= $currentPage === 'home' ? 'active' : '' ?>">Home</a></li>
      <li><a href="/?page=profile" class="<?= $currentPage === 'profile' ? 'active' : '' ?>">Profile</a></li>
      <li><a href="/?page=myposts" class="<?= $currentPage === 'myposts' ? 'active' : '' ?>">My Posts</a></li>
      <li><a href="/?page=dashboard" class="<?= $currentPage === 'dashboard' ? 'active' : '' ?>">Dashboard</a></li>
      <li><a href="/?action=logout">Logout</a></li>
    </ul>
  </div>

</nav>
