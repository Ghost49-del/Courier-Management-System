<?php 
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']=true)
{
  $loggedin=true;
}
else
{
  $loggedin=false;
}

echo '<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
<div class="container-fluid">
  <a class="navbar-brand fw-bold" href="#" style="font-size: 1.5rem; letter-spacing: 0.5px;">
    <i class="fas fa-truck-fast me-2"></i>
    DISPATCH MANAGEMENT SYSTEM
  </a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
      <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="/login/welcome.php">
          <i class="fas fa-home me-1"></i> Home
        </a>
      </li>';

      if(!$loggedin)
      {
      echo '<li class="nav-item">
        <a class="nav-link" href="/login/login.php">
          <i class="fas fa-sign-in-alt me-1"></i> Login
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/login/signup.php">
          <i class="fas fa-user-plus me-1"></i> Signup
        </a>
      </li>';}
      if($loggedin){
      echo '<li class="nav-item">
        <a class="nav-link" href="/login/Logout.php">
          <i class="fas fa-sign-out-alt me-1"></i> Logout
        </a>
      </li>';}

    echo'</ul>
  </div>
</div>
</nav>';
?>