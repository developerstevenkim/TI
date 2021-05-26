<form action method="POST">
      <input type="checkbox" name="hello"/>
</form>

<?php
if(isset($_POST['hello'])) {
    echo '<p>'.$_POST['hello'].'</p>';
}
?>