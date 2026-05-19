# Résoudre le Deadlock d'inscription : Compte non vérifié bloqué

## Contexte du problème

Un utilisateur peut se retrouver **bloqué** dans le scénario suivant :

1. ✅ L'utilisateur crée un compte → un code OTP est envoyé par email
2. ❌ L'utilisateur **ferme la fenêtre OTP** sans entrer le code
3. 🔒 **Login impossible** → `loginUser()` exige `is_verified == 1` (ligne 94 de [AuthController.php](file:///c:/xampp/htdocs/AlphaStore/Controller/AuthController.php#L94))
4. 🔒 **Re-inscription impossible** → `registerUser()` échoue avec `"Email déjà utilisé"` car l'email est déjà en BDD (contrainte UNIQUE, ligne 87-88 de [User.php](file:///c:/xampp/htdocs/AlphaStore/model/User.php#L87-L88))

**Résultat** : L'utilisateur est dans un état de deadlock total.

## Changements proposés

### Stratégie à 3 niveaux

---

### 1. Au Login : Rediriger vers la vérification OTP si le compte existe mais n'est pas vérifié

#### [MODIFY] [AuthController.php](file:///c:/xampp/htdocs/AlphaStore/Controller/AuthController.php)

**Fonction `loginUser()`** — Au lieu de traiter un utilisateur non vérifié comme un échec silencieux, on détecte ce cas et on régénère un nouveau code OTP + on redirige vers `verifie.php`.

```diff
  // 2. Si l'utilisateur existe
- if ($user && $user["is_verified"] == 1) {
+ if ($user && $user["is_verified"] == 1) {
      // ... login normal (inchangé)
  } else {
-     // Log failed attempt (user not found)
-     $loginLog->logLoginAttempt(null, $data['email'], 'failed');
+     // Cas: utilisateur existe mais non vérifié
+     if ($user && $user["is_verified"] == 0) {
+         // Régénérer un nouveau code OTP et renvoyer l'email
+         $code = rand(100000, 999999);
+         $expiry = date("Y-m-d H:i:s", time() + 60 * 30);
+         $userModel = new User();
+         $userModel->updateVerificationCode($data['email'], $code, $expiry);
+         $mailer = new MailerService();
+         $mailer->sendValidationEmail($data['email'], $code);
+         // Retourner un indicateur spécial pour la redirection
+         return ['unverified' => true, 'email' => $data['email']];
+     }
+     // Log failed attempt (user not found)
+     $loginLog->logLoginAttempt(null, $data['email'], 'failed');
  }
```

#### [MODIFY] [signUp.php](file:///c:/xampp/htdocs/AlphaStore/View/html/signUp.php)

Modifier le traitement du résultat de `loginUser()` pour gérer la redirection vers la page OTP :

```diff
  $result = loginUser($dbConnection, $loginData);

- if ($result === true) {
-     header("Location:../my-account/my-account.php");
-     exit;
- } else {
-     $errors_login = [$result];
- }
+ if ($result === true) {
+     header("Location:../my-account/my-account.php");
+     exit;
+ } elseif (is_array($result) && isset($result['unverified'])) {
+     // Compte non vérifié → rediriger vers la page OTP
+     header("Location:verifie.php?email=" . urlencode($result['email']) . "&resent=1");
+     exit;
+ } else {
+     $errors_login = [$result];
+ }
```

---

### 2. Au Signup : Permettre le re-signup si le compte existant n'est pas vérifié

#### [MODIFY] [User.php](file:///c:/xampp/htdocs/AlphaStore/model/User.php)

Ajouter une méthode pour **écraser un compte non vérifié** au lieu de bloquer sur la contrainte UNIQUE :

```php
public function deleteUnverifiedUser($email) {
    $stmt = $this->pdo->prepare("DELETE FROM users WHERE email = ? AND is_verified = 0");
    return $stmt->execute([$email]);
}
```

#### [MODIFY] [AuthController.php](file:///c:/xampp/htdocs/AlphaStore/Controller/AuthController.php)

Modifier `AddUser()` pour supprimer un éventuel compte non vérifié avant d'insérer :

```diff
  function AddUser($data) {
      $errors = validateData($data);
      if (!empty($errors)) {
          return $errors;
      }

+     // Si un compte non vérifié existe déjà avec cet email, le supprimer
+     $user = new User();
+     $existingUser = $user->getUserByEmail($data['email']);
+     if ($existingUser && $existingUser['is_verified'] == 0) {
+         $user->deleteUnverifiedUser($data['email']);
+     }

      $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
      // ... reste inchangé
  }
```

> [!IMPORTANT]
> Cela supprime aussi le profil associé. Il faudra ajouter `ON DELETE CASCADE` sur la clé étrangère `profiles.user_id → users.id`, ou supprimer manuellement le profil avant l'utilisateur.

---

### 3. Sur la page OTP : Ajouter un bouton "Renvoyer le code"

#### [MODIFY] [verifie.php](file:///c:/xampp/htdocs/AlphaStore/View/html/verifie.php)

Ajouter un formulaire pour renvoyer le code OTP et un message de confirmation si le code a été renvoyé :

```html
<!-- Message si code renvoyé -->
<?php if (isset($_GET['resent'])): ?>
    <div class="success-msg">
        <i class='bx bx-check-circle'></i> Un nouveau code a été envoyé à votre email.
    </div>
<?php endif; ?>

<!-- Bouton renvoyer code -->
<form method="POST" action="../../index.php?action=resend_otp" style="margin-top: 15px;">
    <input type="hidden" name="email" value="<?= htmlspecialchars($_GET['email'] ?? '') ?>">
    <button type="submit" class="btn" style="background: transparent; color: #222; border: 2px solid #222;">
        <i class='bx bx-refresh'></i> Renvoyer le code
    </button>
</form>
```

#### [MODIFY] [index.php](file:///c:/xampp/htdocs/AlphaStore/index.php)

Ajouter une nouvelle route `resend_otp` :

```php
if ($action === "resend_otp") {
    require_once(__DIR__ . "/Controller/AuthController.php");
    $email = $_POST["email"] ?? '';
    $result = resendOTP($email);
    header("Location: View/html/verifie.php?email=" . urlencode($email) . "&resent=1");
    exit;
}
```

#### [MODIFY] [AuthController.php](file:///c:/xampp/htdocs/AlphaStore/Controller/AuthController.php)

Ajouter la fonction `resendOTP()` :

```php
function resendOTP($email) {
    $userModel = new User();
    $userData = $userModel->getUserByEmail($email);
    
    if (!$userData || $userData['is_verified'] == 1) {
        return false;
    }
    
    $code = rand(100000, 999999);
    $expiry = date("Y-m-d H:i:s", time() + 60 * 30);
    
    $userModel->updateVerificationCode($email, $code, $expiry);
    
    $mailer = new MailerService();
    return $mailer->sendValidationEmail($email, $code);
}
```

---

## Résumé des fichiers modifiés

| Fichier | Changement |
|---|---|
| [AuthController.php](file:///c:/xampp/htdocs/AlphaStore/Controller/AuthController.php) | `loginUser()` détecte les comptes non vérifiés, `AddUser()` écrase les comptes non vérifiés, nouvelle fonction `resendOTP()` |
| [User.php](file:///c:/xampp/htdocs/AlphaStore/model/User.php) | Nouvelle méthode `deleteUnverifiedUser()` |
| [signUp.php](file:///c:/xampp/htdocs/AlphaStore/View/html/signUp.php) | Gestion de la redirection vers OTP au login |
| [verifie.php](file:///c:/xampp/htdocs/AlphaStore/View/html/verifie.php) | Bouton "Renvoyer le code" + message de confirmation |
| [index.php](file:///c:/xampp/htdocs/AlphaStore/index.php) | Nouvelle route `resend_otp` |
| **BDD** | Ajouter `ON DELETE CASCADE` sur `profiles.user_id` |

## Open Questions

> [!IMPORTANT]
> **Cascade Delete** : La table `profiles` a une clé étrangère vers `users.id`. Quand on supprime un utilisateur non vérifié pour le re-créer, il faut aussi supprimer le profil. Dois-je ajouter `ON DELETE CASCADE` dans la BDD, ou préfères-tu que je supprime le profil manuellement dans le code PHP ?

> [!NOTE]
> **Rate limiting** : Actuellement, il n'y a pas de limite sur le nombre de renvois de code OTP. Veux-tu que j'ajoute un cooldown (par exemple, 60 secondes entre chaque renvoi) pour éviter les abus ?

## Plan de vérification

### Tests automatisés
1. Créer un compte → fermer la fenêtre OTP → tenter de se reconnecter → vérifier la redirection vers `verifie.php`
2. Créer un compte → fermer la fenêtre OTP → tenter de recréer un compte avec le même email → vérifier que ça fonctionne
3. Tester le bouton "Renvoyer le code" → vérifier qu'un nouveau code est généré et envoyé
4. Vérifier qu'un utilisateur déjà vérifié n'est **jamais** supprimé lors d'un re-signup

### Tests manuels
- Parcourir le flux complet dans le navigateur pour chaque scénario
