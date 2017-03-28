<?php  
	if(!empty($_POST) && !empty($_POST['username']) && !empty($_POST['password'])){
		require_once 'inc/db.php';
		require_once 'inc/functions.php';
		$req = $pdo->prepare('SELECT * FROM users WHERE (username = :username OR email = :username) AND confirmed_at IS NOT NULL');
		$req->execute(['username' => $_POST['username']]);
		$user = $req->fetch();
		if(password_verify($_POST['password'], $user->password)){
			session_start();
			$_SESSION['auth'] = $user;
			$_SESSION['flash']['success'] = 'Vous êtes maintenant connecté';
			header('location: account.php');
			exit();
		}
			else{
				$_SESSION['flash']['danger'] = 'Identifiant ou mot de passe incorrect';
			}
	}
?>

<?php require 'inc/header.php'; ?>

<h1>Se connecter</h1>

<form method="POST">

	<div class="form-group">
		<label>Pseudo ou email</label>
		<input type="text" name="username" class="form-control"/>
	</div>

	<div class="form-group">
		<label>Mot de passe <a href="forget.php">(Mot de passe oublié?)</a></label>
		<input type="password" name="password" class="form-control"/>
	</div>

	<div class="form-group">
		<label>
			<input type="checkbox" name="remember" value="1"/> Se souvenir de moi
		</label>
	</div>

	<button type="submit" class="btn btn-primary">Se connecter</button>

</form>

<?php require 'inc/footer.php'; ?>