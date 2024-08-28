document.querySelectorAll(".embed-consent").forEach((node) => {
  const name = node.getAttribute("data-embed-consent-provider");
  const checkbox = node.querySelector("input");

  /**
   * Loads the content of the embed that is currently hidden until consent given
   */
  function loadEmbed() {
    const html = node.querySelector("template").content.textContent;

    // Using createContextualFragment to load script tags
    node.replaceWith(document.createRange().createContextualFragment(html));
    window.removeEventListener("storage", handleStorageChange);
  }

  /**
   * If storage updated to give consent for
   * @param {StorageEvent} e
   */
  function handleStorageChange(e) {
    if (e.key === "embed-consent-" + name && e.newValue === "true") {
      loadEmbed();
    }
  }

  if (checkbox) {
    // If given consent for this provider, can just load it and continue to next node
    if (checkbox && localStorage.getItem("embed-consent-" + name) === "true") {
      loadEmbed();
      return;
    }

    window.addEventListener("storage", handleStorageChange);
  }

  node.querySelector("button").addEventListener("click", () => {
    loadEmbed();

    if (checkbox && checkbox.checked) {
      localStorage.setItem("embed-consent-" + name, "true");

      // Consent always given for provider so show any other embeds for them
      document
        .querySelectorAll(
          "[data-embed-consent-provider=" + name + "].embed-consent"
        )
        .forEach((node) => {
          const html = node.querySelector("template").content.textContent;

          node.replaceWith(
            document.createRange().createContextualFragment(html)
          );
        });
    }
  });
});
