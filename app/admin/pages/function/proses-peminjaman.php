session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
  header('Location: login.php');
  exit;
}