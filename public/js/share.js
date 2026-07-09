// SHARE.JS - Bouton de partage

document.addEventListener("DOMContentLoaded", function () {
  const shareButton = document.getElementById("shareButton");

  if (!shareButton) {
    return;
  }

  shareButton.addEventListener("click", async function () {
    const title = shareButton.dataset.title;
    const url = window.location.origin + "/" + shareButton.dataset.url;
    if (navigator.share) {
      try {
        await navigator.share({
          title: title,
          text: "Regarde cette figurine Warhammer 40k !",
          url: url,
        });
      } catch (err) {}
    } else {
      try {
        await navigator.clipboard.writeText(url);
        const texteOriginal = shareButton.textContent;
        shareButton.textContent = "Lien copié !";
        setTimeout(function () {
          shareButton.textContent = texteOriginal;
        }, 2000);
      } catch (err) {
        prompt("Copiez ce lien :", url);
      }
    }
  });
});
