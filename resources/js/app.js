// MOTION
import Lenis from "lenis"
import { inView, animate } from "motion"

// DYNAMIC FAVICON SWITCHING
document.addEventListener("DOMContentLoaded", () => {
  const faviconLight = document.getElementById("favicon-light")
  const faviconDark = document.getElementById("favicon-dark")

  // Function to update favicon based on color scheme
  function updateFavicon() {
    const isDarkMode = window.matchMedia("(prefers-color-scheme: dark)").matches

    if (isDarkMode) {
      // Use dark mode favicon (_001 - navy background with violet icon)
      faviconLight.setAttribute(
        "href",
        getTemplateDirectoryUri() + "/assets/images/BCAa_logo_sort_RGB.svg"
      )
      faviconDark.setAttribute(
        "href",
        getTemplateDirectoryUri() + "/assets/images/BCAa_logo_hvid_RGB.svg"
      )
    } else {
      // Use light mode favicon (_002 - navy icon on transparent)
      faviconLight.setAttribute(
        "href",
        getTemplateDirectoryUri() + "/assets/images/BCAa_logo_sort_RGB.svg"
      )
      faviconDark.setAttribute(
        "href",
        getTemplateDirectoryUri() + "/assets/images/BCAa_logo_hvid_RGB.svg"
      )
    }
  }

  // Initial favicon setup
  updateFavicon()

  // Listen for changes in color scheme preference
  window
    .matchMedia("(prefers-color-scheme: dark)")
    .addEventListener("change", updateFavicon)

  // Also listen for system theme changes (for better compatibility)
  if (window.matchMedia) {
    const mediaQuery = window.matchMedia("(prefers-color-scheme: dark)")
    mediaQuery.addEventListener("change", updateFavicon)
  }
})

// LOW-FPS DETECTOR
;(function () {
  let samples = 0,
    sum = 0,
    last = performance.now()
  function tick(t) {
    const dt = t - last
    last = t
    if (dt < 1000) {
      sum += dt
      samples++
    }
    if (samples < 30) return requestAnimationFrame(tick)
    const fps = 1000 / (sum / samples)
    if (fps < 45) document.documentElement.classList.add("low-fps")
  }
  requestAnimationFrame(tick)
})()

// LENIS SMOOTH SCROLLING
const isTouch = "ontouchstart" in window || navigator.maxTouchPoints > 0

// Same easing the plugin defaults to when a numeric duration is used
const easeExpoOut = (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t))

// Create instance
const lenis = new Lenis({
  // Match typical plugin behavior
  smoothWheel: true,
  syncTouch: false,
  // Keep duration undefined so easing is used by lerp path (like plugin’s default mode)
  duration: undefined,
  easing: easeExpoOut,
  lerp: 0.1,
  // vertical orientation, window wrapper (defaults)
})

// Disable/soften on touch and low-fps
function applyPerformanceGuards() {
  if (document.documentElement.classList.contains("low-fps")) {
    lenis.options.lerp = 0.25
    lenis.start()
  } else if (isTouch) {
    lenis.options.lerp = 0.2
    lenis.start()
  } else {
    lenis.options.lerp = 0.1
    lenis.start()
  }
}

// Run Lenis RAF
function raf(time) {
  lenis.raf(time)
  requestAnimationFrame(raf)
}
requestAnimationFrame(raf)

// Optional: expose for debugging
window.__lenis = lenis

// Optional: anchor scrolling parity with plugin
document.addEventListener("click", (e) => {
  const a = e
    .composedPath()
    .find(
      (n) =>
        n instanceof HTMLAnchorElement &&
        (n.getAttribute("href")?.startsWith("#") ||
          n.getAttribute("href")?.startsWith("/#") ||
          n.getAttribute("href")?.startsWith("./#"))
    )
  if (!a) return
  const href = a.getAttribute("href")
  if (!href) return
  e.preventDefault()
  let target = 0
  if (!["#", "/#", "./#", "#top", "/#top", "./#top"].includes(href)) {
    const id = `#${href.split("#")[1]}`
    const el = document.querySelector(id)
    if (el) {
      const rect = el.getBoundingClientRect()
      target = window.scrollY + rect.top
    }
  }
  lenis.scrollTo(target, {
    // Use easing/lerp path for consistency with plugin’s motion
    lerp: 0.1,
    easing: easeExpoOut,
  })
})

// SCROLL ANIMATIONS: Using InView to detect when the element is in the viewport
document.addEventListener("DOMContentLoaded", () => {
  inView(
    ".animateOnView",
    (element) => {
      const hasAnimated = element.dataset.animated === "true"
      if (hasAnimated) return

      // Check if element is within the first 75vh of the page
      const elementRect = element.getBoundingClientRect()
      const viewportHeight = window.innerHeight
      const isInFirst75vh = elementRect.top < viewportHeight * 0.75

      // Only animate if element is NOT in the first 75vh
      if (!isInFirst75vh) {
        const opts = isTouch
          ? { delay: 0, duration: 0.35 }
          : { delay: 0.15, duration: 0.55 }
        animate(
          element,
          {
            opacity: [0.25, 1],
            transform: ["translateY(15px)", "translateY(0)"],
          },
          { ...opts, easing: [0.17, 0.55, 0.55, 1] }
        )
        element.dataset.animated = "true"
      } else {
        // Reveal immediately; no animation above the fold
        element.dataset.animated = "true"
      }
    },
    { amount: 0.15 }
  )
})

// NAVIGATION
window.addEventListener("load", function () {
  const mainNavigation = document.getElementById("primary-navigation")
  const mainNavigationToggle = document.getElementById("primary-menu-toggle")

  if (mainNavigation && mainNavigationToggle) {
    mainNavigationToggle.addEventListener("click", function (e) {
      e.preventDefault()

      // Toggle visibility of the menu
      mainNavigation.classList.toggle("hidden")
      mainNavigation.classList.toggle("flex")

      // Check if menu is open
      const isOpen = mainNavigation.classList.contains("flex")

      // Swap hamburger ↔ X icon
      mainNavigationToggle.innerHTML = isOpen
        ? `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="size-9">
                       <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                   </svg>`
        : `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="size-9">
                       <path stroke-linecap="square" stroke-linejoin="square" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                   </svg>`

      // Lock or unlock background scroll
      document.body.classList.toggle("overflow-hidden", isOpen)
    })
  }
})

// TESTIMONIALS
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".js-testimonials").forEach((root) => {
    const slides = Array.from(root.querySelectorAll(".tst-slide"))
    if (!slides.length) return

    let i = 0

    const update = (next) => {
      slides[i].classList.add("hidden")
      i = (next + slides.length) % slides.length
      slides[i].classList.remove("hidden")
      root.querySelectorAll(".js-counter").forEach((el) => {
        el.textContent = i + 1 + "/" + slides.length
      })
    }

    // Init
    slides.forEach((s, idx) => s.classList.toggle("hidden", idx !== 0))
    root
      .querySelectorAll(".js-counter")
      .forEach((el) => (el.textContent = "1/" + slides.length))

    // Delegate clicks
    root.addEventListener("click", (e) => {
      const prev = e.target.closest(".js-prev")
      const next = e.target.closest(".js-next")
      if (!prev && !next) return
      e.preventDefault()
      update(i + (next ? 1 : -1))
    })

    // Keyboard
    root.addEventListener("keydown", (e) => {
      if (e.key === "ArrowRight") update(i + 1)
      if (e.key === "ArrowLeft") update(i - 1)
    })
  })
})

// DYNAMIC HEIGHT MANAGEMENT FOR CODE SNIPPETS (MINIMAL VERSION)

document.addEventListener("DOMContentLoaded", () => {
  const SNIPPET_SEL = ".club-manager-snippet"

  function recalc(snippet) {
    // Let it size itself
    snippet.style.height = "auto"
    // Force a reflow (Safari)
    void snippet.offsetHeight
    // Measure next frame
    requestAnimationFrame(() => {
      const h = snippet.scrollHeight
      const current = parseInt(snippet.style.height, 10) || 0
      if (current !== h) {
        snippet.style.height = h + "px"
      }
    })
  }

  function recalcAll() {
    document.querySelectorAll(SNIPPET_SEL).forEach(recalc)
  }

  // Initial + after full load (fonts/images)
  recalcAll()
  window.addEventListener("load", recalcAll, { passive: true })

  // --- MutationObserver (NO attributes!) ---
  const pending = new Set()
  let rafScheduled = false

  function schedule(snippet) {
    pending.add(snippet)
    if (rafScheduled) return
    rafScheduled = true
    requestAnimationFrame(() => {
      pending.forEach(recalc)
      pending.clear()
      rafScheduled = false
    })
  }

  document.querySelectorAll(SNIPPET_SEL).forEach((snippet) => {
    new MutationObserver((mutations) => {
      // Only react to content changes inside
      if (
        mutations.some(
          (m) => m.type === "childList" || m.type === "characterData"
        )
      ) {
        schedule(snippet)
      }
    }).observe(snippet, {
      childList: true,
      subtree: true,
      characterData: true,
      // attributes: false  ← important! avoids feedback loop
    })
  })

  // Resize debounce
  let resizeTimeout
  window.addEventListener(
    "resize",
    () => {
      clearTimeout(resizeTimeout)
      resizeTimeout = setTimeout(recalcAll, 200)
    },
    { passive: true }
  )
})
