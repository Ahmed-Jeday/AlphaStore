import gsap from 'https://esm.sh/gsap@3.13.0'
import Draggable from 'https://esm.sh/gsap@3.13.0/Draggable'
import { Pane } from 'https://esm.sh/tweakpane@4.0.4'
gsap.registerPlugin(Draggable)

const section = document.querySelector('section.feature-showcase')
const column = section.querySelector('.feature-showcase__column--left')
let detailsElements = section.querySelectorAll('.feature-showcase__detail')
const nextButton = section.querySelector('.feature-showcase__button[data-action="next"]')
const previousButton = section.querySelector('.feature-showcase__button[data-action="previous"]')
const exitButton = section.querySelector('.feature-showcase__button[data-action="exit"]')

const config = {
  theme: 'dark',
  debug: false,
}

const ctrl = new Pane({
  title: 'config',
  expanded: true,
})

const update = () => {
  document.documentElement.dataset.theme = config.theme
  document.documentElement.dataset.debug = config.debug
}

const sync = (event) => {
  if (
    !document.startViewTransition ||
    event.target.controller.view.labelElement.innerText !== 'theme'
  )
    return update()
  document.startViewTransition(() => update())
}


ctrl.addButton({ title: 'reset' }).on('click', () => {
  const currentIndex = getOpenDetails()

  if (currentIndex !== -1) {
    detailsElements[currentIndex].open = false
  }
  const details = column.innerHTML
  column.innerHTML = ''
  requestAnimationFrame(() => {
    column.innerHTML = details
    detailsElements = section.querySelectorAll('details[name="feature"]')
  }) 
})

ctrl.addBinding(config, 'debug')

ctrl.addBinding(config, 'theme', {
  label: 'theme',
  options: {
    system: 'system',
    light: 'light',
    dark: 'dark',
  },
})

ctrl.on('change', sync)
update()

// make tweakpane panel draggable
const tweakClass = 'div.tp-dfwv'
const d = Draggable.create(tweakClass, {
  type: 'x,y',
  allowEventDefault: true,
  trigger: tweakClass + ' button.tp-rotv_b',
})
document.querySelector(tweakClass).addEventListener('dblclick', () => {
  gsap.to(tweakClass, {
    x: `+=${d[0].x * -1}`,
    y: `+=${d[0].y * -1}`,
    onComplete: () => {
      gsap.set(tweakClass, { clearProps: 'all' })
    },
  })
})

// Navigation functionality for details elements
const getOpenDetails = () => {
  return Array.from(detailsElements).findIndex(details => details.open)
}

nextButton?.addEventListener('click', () => {
  const currentIndex = getOpenDetails()

  if (currentIndex !== -1) {
    detailsElements[currentIndex].open = false
    const nextIndex = (currentIndex + 1) % detailsElements.length
    detailsElements[nextIndex].open = true
  }
})

previousButton?.addEventListener('click', () => {
  const currentIndex = getOpenDetails()

  if (currentIndex !== -1) {
    detailsElements[currentIndex].open = false
    const previousIndex = (currentIndex - 1 + detailsElements.length) % detailsElements.length
    detailsElements[previousIndex].open = true
  }
})

exitButton?.addEventListener('click', () => {
  const currentIndex = getOpenDetails()

  if (currentIndex !== -1) {
    detailsElements[currentIndex].open = false
  }
})

const syncState = async () => {
  if (!section.matches(':has([open])')) {
    section.dataset.checkingDetails = false
  } else {
    await Promise.allSettled(section.getAnimations({ subtree: true }).map(a => a.finished))
    section.dataset.checkingDetails = true
  }
}

section.addEventListener('toggle', syncState, true)

// E-commerce interactions
const ctaButtons = section.querySelectorAll('.product-cta__button')
ctaButtons.forEach(btn => {
  btn.addEventListener('click', (e) => {
    const isPrimary = btn.classList.contains('product-cta__button--primary')
    if (isPrimary) {
      gsap.to(btn, {
        scale: 0.95,
        duration: 0.1,
        yoyo: true,
        repeat: 1,
        onComplete: () => {
          btn.textContent = 'Ajouté ✓'
          btn.style.background = '#2d8a4e'
          setTimeout(() => {
            btn.textContent = 'Ajouter au panier'
            btn.style.background = ''
          }, 2000)
        }
      })
    }
  })
})

// ------------------------------------------------------------
// new_front: small product carousel (scroll-snap + buttons)
// ------------------------------------------------------------
const NEW_FRONT_PRODUCTS = {
  laptop: {
    title: 'ASUS ROG Zephyrus G16',
    subtitle: 'Puissance gaming. Design premium. Écran OLED.',
    priceValue: '3 499 DT',
    priceNote: 'ou 145,8 DT/mois',
    details: [
      {
        label: 'Design & Châssis',
        body:
          'Châssis aluminium CNC, finition mate anti-traces. Format 16" ultra-fin avec un poids optimisé pour une mobilité premium. Refroidissement intelligent pour maintenir des performances stables.',
        tags: ['Aluminium CNC', '16"', 'Premium'],
      },
      {
        label: 'CPU & Mémoire',
        body:
          "Processeur dernière génération pour création et gaming. RAM haute fréquence pour un multitâche fluide, et stockage NVMe pour des chargements rapides (apps, jeux, projets).",
        tags: ['CPU Hautes perf', 'RAM DDR5', 'SSD NVMe'],
      },
      {
        label: 'Écran OLED 240Hz',
        body:
          'Écran 16" OLED à haute fréquence pour une fluidité maximale, noirs profonds et couleurs riches. Idéal pour montage, design, et gaming compétitif.',
        tags: ['OLED', '240 Hz', 'Contraste élevé'],
      },
      {
        label: 'GPU & Graphismes',
        body:
          'Carte graphique dédiée pour jeux AAA, rendu 3D et IA. Performances stables grâce au refroidissement optimisé et aux profils de puissance.',
        tags: ['GPU dédié', 'Ray Tracing', 'DLSS/Up-scaling'],
      },
      {
        label: 'Batterie & Connectique',
        body:
          "Autonomie optimisée pour la mobilité. Connectique moderne (USB‑C, HDMI selon config), Wi‑Fi rapide et charge accélérée pour repartir vite.",
        tags: ['USB‑C', 'Wi‑Fi', 'Charge rapide'],
      },
    ],
    images: [
      {
        src: 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=1600&q=80',
        alt: "Laptop premium — vue d'ensemble",
      },
      {
        src: 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?auto=format&fit=crop&w=1600&q=80',
        alt: 'Laptop — clavier rétroéclairé',
      },
      {
        src: 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&w=1600&q=80',
        alt: 'Laptop — écran OLED',
      },
      {
        src: 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?auto=format&fit=crop&w=1600&q=80',
        alt: 'Laptop — performance graphique',
      },
      {
        src: 'https://images.unsplash.com/photo-1527443154391-507e9dc6c5cc?auto=format&fit=crop&w=1600&q=80',
        alt: 'Laptop — ports et connectique',
      },
      {
        src: 'https://images.unsplash.com/photo-1496171367470-9ed9a91ea931?auto=format&fit=crop&w=1600&q=80',
        alt: 'Laptop — setup bureau premium',
      },
    ],
  },
  phone: {
    title: 'iPhone 16 Pro',
    subtitle: 'Titanium. Pro. Ultra fluide.',
    priceValue: '1 199 DT',
    priceNote: 'ou 49,96 DT/mois',
    details: [
      {
        label: 'Design & Finitions',
        body:
          'Titanium finition mate, Ceramic Shield et IP68. Un design premium plus résistant, pensé pour durer au quotidien.',
        tags: ['Titanium', 'IP68', 'Ceramic Shield'],
      },
      {
        label: 'Système Photo Pro',
        body:
          'Capteurs haute résolution, stabilisation optique et traitement HDR avancé. Photos nettes de jour comme de nuit, et vidéos ultra fluides.',
        tags: ['48 MP', 'OIS', 'HDR'],
      },
      {
        label: 'Écran Super Retina XDR',
        body:
          'OLED lumineux et précis, contraste élevé, ProMotion adaptatif pour un défilement ultra fluide et une excellente lisibilité.',
        tags: ['OLED', 'ProMotion', 'HDR'],
      },
      {
        label: 'Puce A18 Pro',
        body:
          'Performances CPU/GPU de pointe et efficacité énergétique optimisée. Idéal pour jeux, montage, et tâches IA.',
        tags: ['3 nm', 'Neural Engine', 'Performance'],
      },
      {
        label: 'Batterie & Charge',
        body:
          'Autonomie améliorée, charge rapide, USB‑C et charge sans fil. Pensé pour une journée complète, voire plus.',
        tags: ['USB‑C', 'Charge rapide', 'Sans fil'],
      },
    ],
    images: [
      {
        src: 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=1600&q=80',
        alt: "iPhone — vue d'ensemble",
      },
      {
        src: 'https://images.unsplash.com/photo-1542751110-97427bbecf20?auto=format&fit=crop&w=1600&q=80',
        alt: 'iPhone — performance en jeu',
      },
      {
        src: 'https://images.unsplash.com/photo-1512499617640-c2f999098c01?auto=format&fit=crop&w=1600&q=80',
        alt: 'iPhone — écran OLED',
      },
      {
        src: 'https://images.unsplash.com/photo-1526001263861-3b29c79b0b34?auto=format&fit=crop&w=1600&q=80',
        alt: 'iPhone — module caméra',
      },
      {
        src: 'https://images.unsplash.com/photo-1512412046876-f386342eddb3?auto=format&fit=crop&w=1600&q=80',
        alt: 'iPhone — charge & mobilité',
      },
      {
        src: 'https://images.unsplash.com/photo-1556656793-08538906a9f8?auto=format&fit=crop&w=1600&q=80',
        alt: 'iPhone — lifestyle',
      },
    ],
  },
  smartwatch: {
    title: 'Smartwatch Pro',
    subtitle: 'Santé, sport, notifications. Tout au poignet.',
    priceValue: '799 DT',
    priceNote: 'ou 33,3 DT/mois',
    details: [
      {
        label: 'Design & Confort',
        body:
          'Boîtier léger et bracelet respirant. Confortable toute la journée, adapté au sport et à la vie quotidienne.',
        tags: ['Léger', 'Confort', 'Résistant'],
      },
      {
        label: 'Santé & Capteurs',
        body:
          'Suivi de fréquence cardiaque, sommeil, activité et alertes. Mesures fiables pour mieux comprendre votre forme.',
        tags: ['Cardio', 'Sommeil', 'Activité'],
      },
      {
        label: 'Écran & Interface',
        body:
          'Écran lumineux et réactif, lisible en extérieur. Navigation fluide pour accéder rapidement aux infos.',
        tags: ['Luminosité', 'Tactile', 'Outdoor'],
      },
      {
        label: 'Sport & GPS',
        body:
          'Modes d’entraînement, suivi GPS et statistiques détaillées. Un vrai coach au poignet.',
        tags: ['GPS', 'Workout', 'Stats'],
      },
      {
        label: 'Autonomie & Charge',
        body:
          'Autonomie optimisée et charge rapide. Prête à repartir en quelques minutes.',
        tags: ['Autonomie', 'Charge rapide', 'Optimisée'],
      },
    ],
    images: [
      {
        src: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=1600&q=80',
        alt: "Smartwatch — vue d'ensemble",
      },
      {
        src: 'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?auto=format&fit=crop&w=1600&q=80',
        alt: 'Smartwatch — sport',
      },
      {
        src: 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?auto=format&fit=crop&w=1600&q=80',
        alt: 'Smartwatch — écran',
      },
      {
        src: 'https://images.unsplash.com/photo-1557180295-76eee20ae8aa?auto=format&fit=crop&w=1600&q=80',
        alt: 'Smartwatch — santé',
      },
      {
        src: 'https://images.unsplash.com/photo-1617043786394-f977fa12eddf?auto=format&fit=crop&w=1600&q=80',
        alt: 'Smartwatch — notifications',
      },
      {
        src: 'https://images.unsplash.com/photo-1526367790999-0150786686a2?auto=format&fit=crop&w=1600&q=80',
        alt: 'Smartwatch — lifestyle',
      },
    ],
  },
}

const escapeHtml = (s) =>
  String(s).replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;')

const applyNewFrontProduct = (root, key) => {
  const data = NEW_FRONT_PRODUCTS[key]
  if (!root || !data) return

  const titleEl = root.querySelector('.product-header__title')
  const subtitleEl = root.querySelector('.product-header__subtitle')
  const priceValueEl = root.querySelector('.product-header__price-value')
  const priceNoteEl = root.querySelector('.product-header__price-note')

  if (titleEl) titleEl.textContent = data.title
  if (subtitleEl) subtitleEl.textContent = data.subtitle
  if (priceValueEl) priceValueEl.textContent = data.priceValue
  if (priceNoteEl) priceNoteEl.textContent = data.priceNote

  const details = Array.from(root.querySelectorAll('.feature-showcase__column--left .feature-showcase__detail'))
  details.forEach((d, idx) => {
    const def = data.details[idx]
    if (!def) return
    const labelEl = d.querySelector('.feature-showcase__label')
    const pEl = d.querySelector('.feature-showcase__content-box p')
    const tagsEl = d.querySelector('.feature-tags')
    if (labelEl) labelEl.textContent = def.label
    if (pEl) pEl.textContent = def.body
    if (tagsEl) {
      tagsEl.innerHTML = def.tags.map((t) => `<span class="feature-tag">${escapeHtml(t)}</span>`).join('')
    }
  })

  // reset open state to first detail
  details.forEach((d, i) => {
    d.open = i === 0
  })

  const imgs = Array.from(
    root.querySelectorAll('.feature-showcase__column--right .feature-showcase__img-wrapper img')
  )
  imgs.forEach((img, idx) => {
    const def = data.images[idx]
    if (!def) return
    img.src = def.src
    img.alt = def.alt
  })
}

const initNewFrontCarousel = () => {
  document.querySelectorAll('.new_front .nf-carousel').forEach((carousel) => {
    const track = carousel.querySelector('.nf-carousel__track')
    if (!track) return

    const root = carousel.closest('.new_front')

    const btnPrev = carousel.querySelector('.nf-carousel__btn[data-action="prev"]')
    const btnNext = carousel.querySelector('.nf-carousel__btn[data-action="next"]')
    const items = Array.from(track.querySelectorAll('.nf-carousel__item'))

    const scrollByCard = (dir) => {
      const card = items[0]
      const step = card ? (card.getBoundingClientRect().width + 14) : 260
      track.scrollBy({ left: dir * step, behavior: 'smooth' })
    }

    btnPrev?.addEventListener('click', (e) => {
      e.preventDefault()
      scrollByCard(-1)
    })

    btnNext?.addEventListener('click', (e) => {
      e.preventDefault()
      scrollByCard(1)
    })

    items.forEach((item) => {
      item.addEventListener('click', (e) => {
        e.preventDefault()
        items.forEach((x) => x.classList.remove('is-active'))
        item.classList.add('is-active')
        item.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'start' })

        const key = item.getAttribute('data-product')
        applyNewFrontProduct(root, key)
      })
    })

    // initial sync with current active item
    const active = items.find((x) => x.classList.contains('is-active')) || items[0]
    const key = active?.getAttribute('data-product')
    applyNewFrontProduct(root, key)
  })
}

// Run after module executes (safe for this page)
initNewFrontCarousel()