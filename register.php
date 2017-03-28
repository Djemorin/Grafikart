<?php

	require_once 'inc/functions.php';

	session_start();

	if (!empty($_POST)) {
		
		$errors=array();

		require_once 'inc/db.php';

		if (empty($_POST['username']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username'])) {
			$errors['username'] = "Votre pseudo n'est pas valide";
		}
			else{
				$req = $pdo -> prepare('SELECT id FROM users WHERE username = ?');
				$req -> execute([$_POST['username']]);
				$user = $req -> fetch();
				
				if($user){
					$errors['username'] = 'Pseudo déjà pris. Les boules.';
				}
			}
		
		if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$errors['email'] = "Votre email n'est pas valide";
		}
			else{
				$req = $pdo -> prepare('SELECT id FROM users WHERE email = ?');
				$req -> execute([$_POST['email']]);
				$user = $req -> fetch();
				
				if($user){
					$errors['email'] = 'Email déjà utilisé... Alzheimer?';
				}
			}

		if (empty($_POST['password']) || $_POST['password'] != $_POST['password_confirm']){
			$errors['password'] = "Vous devez entrer un mot de passe valide";
		}

		if(empty($errors)){


			$req = $pdo -> prepare("INSERT INTO users SET username = ?, password = ?, email = ?, confirmation_token = ?");
			$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
			$token = str_random(60);
			$req -> execute([$_POST['username'], $password, $_POST['email'], $token]);
			$user_id = $pdo -> lastInsertId();

			mail($_POST['email'], 'Activation de votre compte', "Merci de cliquer sur ce lien pour activer votre compte\n\nlocalhost/grafikart/confirm.php?id=$user_id&token=$token");
			$_SESSION['flash']['success'] = 'Un email de confirmation vous a été envoyé pour valider votre compte';

			header('location: login.php');
			exit();

		}
	}
?>

<?php require 'inc/header.php'; ?>

<h1>S'inscrire</h1>

<?php if(!empty($errors)): ?>
	
	<div class="alert alert-danger">
		<p>Vous n'avez pas rempli le formulaire correctement. Boulet.</p>
		
		<ul>
			<?php foreach ($errors as $error): ?>
				<li><?= $error; ?></li>
			<?php endforeach; ?>
		</ul>		
	</div>
<?php endif; ?>

<form method="POST">

	<div class="form-group">
		<label>Pseudo</label>
		<input type="text" name="username" class="form-control"/>
	</div>

	<div class="form-group">
		<label>Email</label>
		<input type="email" name="email" class="form-control"/>
	</div>

	<div class="form-group">
		<label>Mot de passe</label>
		<input type="password" name="password" class="form-control"/>
	</div>

	<div class="form-group">
		<label>Confirmez votre mot de passe</label>
		<input type="password" name="password_confirm" class="form-control"/>
	</div>

	<button type="submit" class="btn btn-primary">M'inscrire</button>

</form>

<?php require 'inc/footer.php'; ?>
