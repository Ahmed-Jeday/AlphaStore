document.getElementById('updateProfileBtn').addEventListener('click', async () => {
    const profileData = {
        firstName: document.getElementById('firstName').value,
        lastName: document.getElementById('lastName').value,
        email: document.getElementById('email').value,
        age: document.getElementById('age').value,
        phone: document.getElementById('phone').value,
        gender: document.getElementById('gender').value ,
        avatar: document.getElementById("profileAvatar").src


    };

    const response = await fetch('../../index.php?action=updateProfile', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(profileData)
    });

    const result = await response.json();
    if (result.success) {
        alert("Profil mis à jour !");
        window.location.reload(); // Pour voir les changements via les variables de session
    }
    else{
        alert("Erreur lors de la mise à jour du profil.");
        window.location.reload();
    }
});