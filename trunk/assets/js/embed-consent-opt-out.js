document.querySelectorAll(".embed-consent-opt-out").forEach((node) => {
  const noSitesText = node.querySelector(".embed-consent-opt-out-no-sites");
  const hasSitesText = node.querySelector(".embed-consent-opt-out-has-sites");
  const siteOptOuts = node.querySelector(".embed-consent-opt-out-sites");

  if (!noSitesText || !hasSitesText || !siteOptOuts) {
    return;
  }

  const consentKeys = () => {
    const keys = [];

    for (let i = 0; i < localStorage.length; i++) {
      if (localStorage.key(i).startsWith("embed-consent-")) {
        keys.push(localStorage.key(i));
      }
    }

    return keys;
  };

  const render = () => {
    if (!consentKeys().length) {
      noSitesText.style.display = "";
      hasSitesText.style.display = "none";
      siteOptOuts.style.display = "none";
      return;
    }

    siteOptOuts
      .querySelectorAll("[data-embed-consent-provider]")
      .forEach((node) => {
        const name = node.getAttribute("data-embed-consent-provider");
        if (localStorage.getItem("embed-consent-" + name) === "true") {
          node.style.display = "";
        } else {
          node.style.display = "none";
        }
      });

    noSitesText.style.display = "none";
    hasSitesText.style.display = "";
    siteOptOuts.style.display = "";
  };

  // Watch localstorage for changes so opt-in from another tab is reflected
  window.addEventListener("storage", (e) => {
    if (e.key && e.key.startsWith("embed-consent-")) {
      render();
    }
  });

  siteOptOuts.addEventListener("click", (e) => {
    const target = e.target;
    if (target && target.tagName === "BUTTON") {
      const parent = target.closest("[data-embed-consent-provider]");
      if (parent) {
        const name = parent.getAttribute("data-embed-consent-provider");
        localStorage.removeItem("embed-consent-" + name);
      } else {
        consentKeys().forEach((key) => localStorage.removeItem(key));
      }

      render();
    }
  });

  render();
});
